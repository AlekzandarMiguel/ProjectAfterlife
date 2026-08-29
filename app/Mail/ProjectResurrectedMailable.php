<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectResurrectedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project,
        public string $summary
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Official Induction: {$this->project->title} is Resurrected into the Hall of Fame");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.project-resurrected');
    }
}
