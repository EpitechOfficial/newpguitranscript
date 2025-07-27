<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class TranscriptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfTranscript;
    public $pdfLetter;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $pdfTranscript, $pdfLetter)
    {
        $this->data = $data;
        $this->pdfTranscript = $pdfTranscript;
        $this->pdfLetter = $pdfLetter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Academic Transcript - ' . $this->data['biodata']->Surname . ' ' . $this->data['biodata']->Othernames,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.partials.transcript_letter',
            with: [
                'biodata' => $this->data['biodata'],
                'results' => $this->data['results'],
                'degreeAwarded' => $this->data['degreeAwarded'],
                'dateAward' => $this->data['dateAward'],
                'cgpa' => $this->data['cgpa'],
                'gender' => $this->data['gender'],
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
        return [
            Attachment::fromData(fn () => $this->pdfTranscript, 'transcript.pdf')
                ->withMime('application/pdf'),
            Attachment::fromData(fn () => $this->pdfLetter, 'transcript_letter.pdf')
                ->withMime('application/pdf'),
        ];
    }
} 