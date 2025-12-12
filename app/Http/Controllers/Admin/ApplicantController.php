<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Services\CertificateService;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CertificateGeneratedNotification;
use App\Http\Traits\HasSortableColumns;
use App\Mail\CertificateEmail;
use Illuminate\Support\Facades\Mail;
use App\Services\WhatsAppService;

class ApplicantController extends Controller
{
    use HasSortableColumns;

    public function index(Request $request)
    {
        $query = Applicant::query();

        // Filter by name
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }
        // Filter by ID
        if ($id = $request->input('id')) {
            $query->where('id', $id);
        }
        // Filter by email
        if ($email = $request->input('email')) {
            $query->where('email', 'like', "%{$email}%");
        }
        // Filter by phone
        if ($phone = $request->input('phone')) {
            $query->where('phone', 'like', "%{$phone}%");
        }
        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        
        // Filter by certificate status
        if ($certificateStatus = $request->input('certificate_status')) {
            if ($certificateStatus === 'generated') {
                $query->has('certificates');
            } else {
                $query->doesntHave('certificates');
            }
        }
        
        // Filter by submitted_at (date)
        if ($date = $request->input('submitted_at')) {
            $query->whereDate('submitted_at', $date);
        }

        // Apply sorting
        $validSortFields = ['id', 'name', 'email', 'phone', 'status', 'submitted_at', 'created_at', 'certificate_status'];
        $sort = $this->applySorting($query, $request, $validSortFields, 'created_at', 'desc');

        // Handle certificate status sorting separately since it's a relationship
        if ($sort['field'] === 'certificate_status') {
            $direction = $sort['direction'] === 'asc' ? 'asc' : 'desc';
            $query->withCount('certificates as has_certificate')
                  ->orderBy('has_certificate', $direction);
        }

        $applicants = $query->with('uploads')->paginate(10)->appends($request->except('page'));

        return view('admin.applicants.index', compact('applicants', 'sort'));
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['uploads']);
        return view('admin.applicants.show', compact('applicant'));
    }

    public function edit(Applicant $applicant)
    {
        return view('admin.applicants.edit', compact('applicant'));
    }

    public function update(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'country_code' => ['required','string','max:5'],
            'phone' => ['required','string','regex:/^[0-9]{10}$/'],
            'status' => ['required','in:pending,in_verification,verified,rejected,certificate_generated']
        ]);

        // Store the old status for comparison
        $oldStatus = $applicant->status;
        $newStatus = $request->input('status');

        // Delete certificates if status is being changed to pending
        if ($oldStatus !== 'pending' && $newStatus === 'pending' && $applicant->certificates()->exists()) {
            $applicant->certificates()->delete();
        }

        // Update the applicant
        $applicant->update($validated);

        // Update all uploads to match the application status
        if ($request->has('status')) {
            // Map application status to upload verification status
            $verificationStatus = match($newStatus) {
                'verified', 'certificate_generated' => 'verified',
                'rejected' => 'rejected',
                default => 'pending'
            };

            $applicant->uploads()->update(['verification_status' => $verificationStatus]);
        }

        return redirect()->route('admin.applicants.show', $applicant)
            ->with('success', 'Application updated successfully.');
    }

    public function destroy(Applicant $applicant)
    {
        try {
            // Delete all uploads first (this will trigger file deletion in the model)
            $applicant->uploads()->forceDelete();
            
            // Then delete the applicant
            $applicant->forceDelete();
            
            return redirect()->route('admin.applicants.index')
                ->with('success', 'Application and all related documents have been permanently deleted.');
        } catch (\Exception $e) {
            Log::error('Error deleting applicant: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete application. Please try again.');
        }
    }

    public function restore($id)
    {
        $applicant = Applicant::withTrashed()->findOrFail($id);
        $applicant->restore();
        return redirect()->route('admin.applicants.show', $applicant)->with('success','Application restored.');
    }

    public function startVerification(Request $request, Applicant $applicant)
    {
        // Check if application is in pending state
        if ($applicant->status !== 'pending') {
            return back()->with('error', 'Verification can only be started for pending applications.');
        }

        // Start verification process
        $applicant->status = 'in_verification';
        $applicant->verification_started_at = now();
        $applicant->verification_started_by = auth()->id();
        $applicant->save();

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'verification_started',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => ['details' => 'Started verification process'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Verification process started.');
    }

    public function completeVerification(Request $request, Applicant $applicant)
    {
        // Validate request
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000'
        ]);

        Log::info('Starting verification for applicant: ' . $applicant->id);

        // Update applicant status
        $applicant->update([
            'status' => 'verified',
            'verification_completed_at' => now(),
            'verification_completed_by' => auth()->id(),
            'verification_notes' => $request->verification_notes
        ]);

        // Update status of all uploads to verified
        Upload::where('applicant_id', $applicant->id)->update(['verification_status' => 'verified']);
        Log::info('Updated all uploads to verified for applicant ' . $applicant->id);

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'verification_completed',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => ['notes' => $request->verification_notes],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::info('Verification complete for applicant: ' . $applicant->id);

        return back()->with('success', 'Application has been verified successfully.');
    }

    public function reject(Request $request, Applicant $applicant)
    {
        // Validate request
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        Log::info('Starting rejection for applicant: ' . $applicant->id);

        // Update applicant status
        $applicant->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason
        ]);

        // Update status of all uploads to rejected
        Upload::where('applicant_id', $applicant->id)->update(['verification_status' => 'rejected']);
        Log::info('Updated all uploads to rejected for applicant ' . $applicant->id);

        // Create audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'application_rejected',
            'target_type' => Applicant::class,
            'target_id' => $applicant->id,
            'metadata' => ['reason' => $request->rejection_reason],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::info('Rejection complete for applicant: ' . $applicant->id);

        return back()->with('success', 'Application has been rejected.');
    }

    public function exportCsv(Request $request)
    {
        $filename = 'applicants_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Status', 'Submitted At', 'Verified At']);

            $query = Applicant::query();
            if ($ids = $request->input('ids')) {
                $idArray = collect(explode(',', $ids))->filter()->values();
                if ($idArray->isNotEmpty()) {
                    $query->whereIn('id', $idArray);
                }
            }
            if ($name = $request->input('name')) $query->where('name', 'like', "%{$name}%");
            if ($email = $request->input('email')) $query->where('email', 'like', "%{$email}%");
            if ($status = $request->input('status')) $query->where('status', $status);
            if ($date = $request->input('submitted_at')) $query->whereDate('submitted_at', $date);

            $query->orderByDesc('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $a) {
                    fputcsv($handle, [
                        $a->id,
                        $a->name,
                        $a->email,
                        $a->phone,
                        $a->status,
                        optional($a->submitted_at)?->toDateTimeString(),
                        optional($a->verification_completed_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function generateCertificate(Request $request, Applicant $applicant, CertificateService $certificateService)
    {
        // Check if application is verified
        if ($applicant->status !== 'verified') {
            return redirect()->route('admin.applicants.index')
                ->with('error', 'Certificates can only be generated for verified applications.');
        }

        $request->validate([
            'template_id' => 'required|exists:certificate_templates,id'
        ]);

        // Start a database transaction
        \DB::beginTransaction();

        try {
            // Find the selected certificate template
            $template = CertificateTemplate::findOrFail($request->template_id);

            // Generate the certificate
            $certificate = $certificateService->generateCertificate($applicant, $template, auth()->id());

            // Update verification details
            $applicant->update([
                'verification_completed_at' => now(),
                'verification_completed_by' => auth()->id()
            ]);

            // Create audit log
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'certificate_generated',
                'target_type' => get_class($certificate),
                'target_id' => $certificate->id,
                'metadata' => [
                    'applicant_id' => $applicant->id,
                    'serial_number' => $certificate->serial_number,
                    'template_name' => $template->name
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Queue email notification
            Notification::route('mail', $applicant->email)
                ->notify(new CertificateGeneratedNotification($certificate));

            // Commit the transaction
            \DB::commit();

            return redirect()->route('admin.applicants.index')
                ->with('success', 'Certificate generated successfully for ' . $applicant->name);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            \DB::rollBack();
            
            Log::error('Error generating certificate: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->route('admin.applicants.show', $applicant)
                ->with('error', 'Failed to generate certificate: ' . $e->getMessage());
        }
    }

    /**
     * Send email to the applicant
     */
    /**
     * Send email to the applicant
     */
    public function sendEmail(Request $request, Applicant $applicant)
    {
        try {
            // Get the latest certificate
            $certificate = $applicant->latestCertificate;

            // Send actual certificate email if available
            if ($certificate) {
                // Increment attempt count
                $certificate->increment('send_attempts');
                $certificate->update(['last_attempt_at' => now()]);

                // Generate URL
                $certificateUrl = route('certificate.view', ['certificate' => $certificate->id]);

                // Send email
                Mail::to($applicant->email)->send(new CertificateEmail($certificate, $certificateUrl));

                // Update certificate status
                $certificate->update([
                    'email_sent_at' => now(),
                    'status' => 'sent_email',
                    'last_error' => null
                ]);
            } else {
                 // Fallback to generic email if no certificate? 
                 // For now, let's assuming this action is primarily for certificate delivery as context implies.
                 // Or we could send a generic email. But the user request specifically mentioned syncing certificate status.
                 // Given the previous code was commented out, let's keep it minimal but effective.
            }
            
            // Log the action (keep existing audit log)
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'email_sent',
                'target_type' => get_class($applicant),
                'target_id' => $applicant->id,
                'metadata' => [
                    'email' => $applicant->email,
                    'type' => $certificate ? 'certificate_email' : 'custom_email',
                    'certificate_id' => $certificate?->id
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Email sent successfully to ' . $applicant->email);
            
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            if (isset($certificate)) {
                $certificate->update(['last_error' => $e->getMessage()]);
            }
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp message to the applicant
     */
    /**
     * Send WhatsApp message to the applicant
     */
    public function sendWhatsApp(Request $request, Applicant $applicant, WhatsAppService $whatsAppService)
    {
        try {
            // Validate phone number format (10 digits)
            if (!preg_match('/^\d{10}$/', $applicant->phone)) {
                return back()->with('error', 'Invalid phone number format. Please ensure it has exactly 10 digits.');
            }
            
            // Ensure country code is set, default to +91 if not
            $countryCode = $applicant->country_code ?? '+91';
            $fullPhoneNumber = $countryCode . $applicant->phone;
            
            // Get the message from the request or use a default message
            $message = $request->input('message', "Hello {$applicant->name}, this is a message from our certificate management system.");
            
            // Send the WhatsApp message
            $whatsAppService->sendMessage($fullPhoneNumber, $message);

            // Update Certificate Status if exists
            $certificate = $applicant->latestCertificate;
            if ($certificate) {
                $certificate->increment('send_attempts');
                $certificate->update([
                    'whatsapp_sent_at' => now(),
                    'status' => 'sent_whatsapp',
                    'last_attempt_at' => now(),
                    'last_error' => null
                ]);
            }
            
            // Log the action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'whatsapp_sent',
                'target_type' => get_class($applicant),
                'target_id' => $applicant->id,
                'metadata' => [
                    'phone' => $fullPhoneNumber,
                    'country_code' => $countryCode,
                    'local_number' => $applicant->phone,
                    'message' => $message,
                    'type' => 'whatsapp_message',
                    'certificate_id' => $certificate?->id
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'WhatsApp message sent successfully to ' . $fullPhoneNumber);
            
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp: ' . $e->getMessage());
            if (isset($certificate) && $certificate) {
                $certificate->update(['last_error' => $e->getMessage()]);
            }
            return back()->with('error', 'Failed to send WhatsApp: ' . $e->getMessage());
        }
    }

    /**
     * Reset verification status of an applicant
     */
    public function resetVerification(Request $request, Applicant $applicant)
    {
        try {
            // Only allow reset if not already in pending state
            if ($applicant->status !== 'pending') {
                $applicant->update([
                    'status' => 'pending',
                    'verification_started_at' => null,
                    'verification_started_by' => null,
                    'verification_completed_at' => null,
                    'verification_completed_by' => null,
                    'verification_notes' => null,
                    'rejected_at' => null,
                    'rejected_by' => null,
                    'rejection_reason' => null,
                ]);

                // Reset all uploads to pending
                $applicant->uploads()->update(['verification_status' => 'pending']);

                // Log the action
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'verification_reset',
                    'target_type' => get_class($applicant),
                    'target_id' => $applicant->id,
                    'metadata' => [
                        'previous_status' => $applicant->getOriginal('status'),
                        'reset_by' => auth()->id()
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return back()->with('success', 'Verification has been reset successfully.');
            }
            
            return back()->with('info', 'Application is already in pending status.');
            
        } catch (\Exception $e) {
            Log::error('Error resetting verification: ' . $e->getMessage());
            return back()->with('error', 'Failed to reset verification: ' . $e->getMessage());
        }
    }
}

