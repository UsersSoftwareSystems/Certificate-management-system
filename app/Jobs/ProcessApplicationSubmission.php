<?php

namespace App\Jobs;

use App\Models\Applicant;
use App\Models\User;
use App\Notifications\AdminNewSubmissionNotification;
use App\Notifications\ApplicantSubmittedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessApplicationSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Applicant $applicant,
        public bool $isNewSubmission
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing application submission job', ['applicant_id' => $this->applicant->id]);

        if ($this->isNewSubmission) {
            try {
                // Notify Applicant
                Notification::route('mail', $this->applicant->email)
                    ->notify(new ApplicantSubmittedNotification($this->applicant));

                // Notify Admins
                $admin = User::role('Super Admin')->first();
                if ($admin) {
                    $admin->notify(new AdminNewSubmissionNotification($this->applicant));
                }
                
                Log::info('Submission notifications sent successfully', ['applicant_id' => $this->applicant->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send submission notifications in background job', [
                    'error' => $e->getMessage(),
                    'applicant_id' => $this->applicant->id
                ]);
            }
        }
    }
}
