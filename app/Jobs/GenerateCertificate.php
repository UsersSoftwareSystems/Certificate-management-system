<?php

namespace App\Jobs;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\CertificateTemplate;
use App\Notifications\CertificateGeneratedNotification;
use App\Services\CertificateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GenerateCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Applicant $applicant,
        public CertificateTemplate $template,
        public int $adminId,
        public ?string $ipAddress = null,
        public ?string $userAgent = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CertificateService $certificateService): void
    {
        Log::info('Starting background certificate generation', ['applicant_id' => $this->applicant->id]);

        DB::beginTransaction();

        try {
            // Generate the certificate
            $certificate = $certificateService->generateCertificate(
                $this->applicant, 
                $this->template, 
                $this->adminId
            );

            // Update verification details
            $this->applicant->update([
                'verification_completed_at' => now(),
                'verification_completed_by' => $this->adminId
            ]);

            // Create audit log
            AuditLog::create([
                'user_id' => $this->adminId,
                'action' => 'certificate_generated',
                'target_type' => get_class($certificate),
                'target_id' => $certificate->id,
                'metadata' => [
                    'applicant_id' => $this->applicant->id,
                    'serial_number' => $certificate->serial_number,
                    'template_name' => $this->template->name,
                    'job_processed' => true
                ],
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);

            // Queue email notification - DISABLED per user request (manual only)
            // Notification::route('mail', $this->applicant->email)
            //     ->notify(new CertificateGeneratedNotification($certificate));

            DB::commit();

            Log::info('Certificate generated successfully in background', ['certificate_id' => $certificate->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Background certificate generation failed', [
                'error' => $e->getMessage(),
                'applicant_id' => $this->applicant->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Optionally: Set a flag on the applicant or create a failed notification/log so admin knows
        }
    }
}
