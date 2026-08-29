<?php

namespace App\Mail;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdoptionStatusMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AdoptionRequest $adoptionRequest,
        public string $status,
        public ?string $feedback = null
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? "Adoption Approved: You are now the steward of {$this->adoptionRequest->project->title}"
            : "Adoption Request Update: {$this->adoptionRequest->project->title}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.adoption-status');
    }
}
