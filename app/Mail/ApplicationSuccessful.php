<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    public Applicant $applicant;
    public bool $isNew;

    /**
     * Create a new message instance.
     */
    public function __construct(Applicant $applicant, bool $isNew = true)
    {
        $this->applicant = $applicant;
        $this->isNew = $isNew;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Submitted Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application_successful',
            with: [
                'applicantName' => trim("{$this->applicant->first_name} {$this->applicant->last_name}"),
                'applicationNo' => $this->applicant->application_no,
                'isNew'         => $this->isNew,
            ],
        );
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
