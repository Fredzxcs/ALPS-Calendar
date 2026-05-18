<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DriverNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $training;
    public $coordinator;
    public $isUpdate;

    public function __construct($training, $coordinator, bool $isUpdate = false)
    {
        $this->training = $training;
        $this->coordinator = $coordinator;
        $this->isUpdate = $isUpdate;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isUpdate
                ? 'Driver Arrangement Update Notification'
                : 'Driver Arrangement Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.driver_notification',
            with: [
                'training' => $this->training,
                'coordinator' => $this->coordinator,
                'isUpdate' => $this->isUpdate,
            ],
        );
    }

    public function build()
    {
        return $this->from('no-reply@alpscalendar.com', 'ALPS Calendar')
                    ->subject($this->isUpdate ? 'ALPS Calendar: Driver Arrangement Update Notification' : 'ALPS Calendar: Driver Arrangement Notification')
                    ->view('emails.driver_notification')
                    ->with([
                        'training' => $this->training,
                        'coordinator' => $this->coordinator,
                        'isUpdate' => $this->isUpdate,
                    ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
