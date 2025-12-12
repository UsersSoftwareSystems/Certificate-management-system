<?php

namespace App\Notifications;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class CertificateGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Certificate $certificate)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = Storage::disk('public')->url($this->certificate->pdf_path);

        return (new MailMessage)
            ->subject('Your Certificate is Ready')
            ->greeting('Hello ' . $this->certificate->applicant->name . ',')
            ->line('Your certificate has been generated successfully.')
            ->line('Serial Number: ' . $this->certificate->serial_number)
            ->action('View Certificate', $url)
            ->line('Thank you for using our service!');
    }
}


