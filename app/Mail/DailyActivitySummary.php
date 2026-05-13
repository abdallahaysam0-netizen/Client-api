<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyActivitySummary extends Mailable
{
    use Queueable, SerializesModels;

    public $notesCount;
    public $attachmentsCount;
    public $recentNotes;
    public $recentAttachments;

    /**
     * Create a new message instance.
     */
    public function __construct($notesCount, $attachmentsCount, $recentNotes, $recentAttachments)
    {
        $this->notesCount = $notesCount;
        $this->attachmentsCount = $attachmentsCount;
        $this->recentNotes = $recentNotes;
        $this->recentAttachments = $recentAttachments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Activity Summary - ' . now()->format('Y-m-d'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_summary',
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
