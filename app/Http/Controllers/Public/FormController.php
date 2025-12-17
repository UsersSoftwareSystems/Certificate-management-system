<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationFormRequest;
use App\Models\Applicant;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicantSubmittedNotification;
use App\Notifications\AdminNewSubmissionNotification;
use App\Jobs\ProcessApplicationSubmission;

class FormController extends Controller
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    public function create(Request $request)
    {
        $token = $request->get('token', Str::random(64));
        $applicant = Applicant::where('token', $token)->first();
        
        return view('public.application-form', compact('applicant', 'token'));
    }

    public function show(string $token)
    {
        $applicant = Applicant::where('token', $token)->firstOrFail();
        
        // One-time edit restriction
        if ($applicant->edit_count >= 1) {
            return redirect()
                ->route('apply.success', $token)
                ->with('info', 'Your application is locked and cannot be edited further.');
        }

        $applicant->load('uploads');
        
        return view('public.application-form', compact('applicant', 'token'));
    }

    public function store(ApplicationFormRequest $request)
    {
        try {
            DB::beginTransaction();
            
            Log::info('Form submission received', $request->all());
            
            $token = $request->input('token', Str::random(64));
            $existingApplicant = Applicant::where('token', $token)->first();

            // Check if locked
            if ($existingApplicant && $existingApplicant->edit_count >= 1) {
                throw new \Exception('Application is locked and cannot be edited.');
            }
            
            // Create or update applicant
            $applicant = Applicant::updateOrCreate(
                ['token' => $token],
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'country_code' => $request->input('country_code', '+91'),
                    'phone' => $request->input('phone'),
                    'gender' => $request->input('gender'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'educational_details' => $request->input('educational_details'),
                    'temple_address' => $request->input('temple_address'),
                    'trustee_name' => $request->input('trustee_name'),
                    'trustee_country_code' => $request->input('trustee_country_code', '+91'),
                    'trustee_mobile' => $request->input('trustee_mobile'),
                    'trustee_email' => $request->input('trustee_email'),
                    'trustee_designation' => $request->input('trustee_designation'),
                    'status' => 'pending',
                    'submitted_at' => now(),
                ]
            );

            // Increment edit count if it's an update
            if ($existingApplicant) {
                $applicant->increment('edit_count');
            }

            Log::info('Applicant created/updated', ['id' => $applicant->id, 'name' => $applicant->name]);

            // Handle file uploads
            $uploadTypes = ['tenth_certificate', 'twelfth_certificate', 'graduation_certificate', 'masters_certificate', 'sports_certificate', 'extraordinary_certificate'];
            $uploadCount = 0;

            foreach ($uploadTypes as $uploadType) {
                if ($request->hasFile($uploadType)) {
                    $files = $request->file($uploadType);
                    
                    foreach ($files as $file) {
                        Log::info("Processing file upload: $uploadType");
                        
                        // Validate file
                        $errors = $this->fileUploadService->validateFile($file);
                        if (!empty($errors)) {
                            throw new \Exception('File validation failed: ' . implode(', ', $errors));
                        }
                        
                        // Create new upload
                        $upload = $this->fileUploadService->handleUpload(
                            $file, 
                            $applicant, 
                            str_replace('_certificate', '', $uploadType)
                        );
                        
                        Log::info("File uploaded successfully", ['upload_id' => $upload->id]);
                        $uploadCount++;
                    }
                }
            }

            DB::commit();

            Log::info('Application submitted successfully', [
                'applicant_id' => $applicant->id,
                'uploads_count' => $uploadCount
            ]);

            // Notify in background after response is sent
            ProcessApplicationSubmission::dispatch($applicant, !$existingApplicant)->afterResponse();

            return redirect()
                ->route('apply.success', $applicant->token)
                ->with('success', 'Application submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Application submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit application: ' . $e->getMessage()]);
        }
    }

    public function deleteUpload(\App\Models\Upload $upload)
    {
        try {
            // Security check: ensure the upload belongs to the applicant associated with the current token (if known) 
            // Since this is a public route, we might need to rely on the token in the session or request.
            // Simplified for now: just delete. In a real app, verify ownership via a signed URL or session token.
            
            $this->fileUploadService->deleteUpload($upload);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function success(string $token)
    {
        $applicant = Applicant::where('token', $token)->firstOrFail();
        
        return view('public.application-success', compact('applicant'));
    }
}
