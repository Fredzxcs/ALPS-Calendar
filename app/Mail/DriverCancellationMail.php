<?php

namespace App\Mail;

use App\Models\Training;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DriverCancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $training;
    public $coordinator;

    public function __construct(Training $training, ?User $coordinator)
    {
        $this->training = $training;
        $this->coordinator = $coordinator;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Driver Arrangement Cancellation Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.driver_cancellation_notification',
            with: [
                'training' => $this->training,
                'coordinator' => $this->coordinator,
            ],
        );
    }

    public function build()
    {
        return $this->from('no-reply@alpscalendar.com', 'ALPS Calendar')
            ->subject('ALPS Calendar: Driver Arrangement Cancellation Notification')
            ->view('emails.driver_cancellation_notification')
            ->with([
                'training' => $this->training,
                'coordinator' => $this->coordinator,
            ]);
    }

    public function attachments(): array
    {
        return [];
    }
}