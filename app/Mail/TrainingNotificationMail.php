<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $training;
    public $facilitator;

    /**
     * Create a new message instance.
     */
    public function __construct($training, $facilitator)
    {
        $this->training = $training;
        $this->facilitator = $facilitator;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Training Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.training_notification',
            with: [
                'training' => $this->training,
                'facilitator' => $this->facilitator,
            ],
        );
    }

    public function build()
    {
        return $this->from('no-reply@alpscalendar.com', 'ALPS Calendar')
                    ->subject('ALPS Calendar: Training Notification')
                    ->view('emails.training-notification')
                    ->with([
                        'training' => $this->training,
                        'facilitator' => $this->facilitator,
                    ]);
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
