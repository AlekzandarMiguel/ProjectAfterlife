<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InactivityWarningMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project,
        public int $daysInactive
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Inactivity Notice: Recovery update required for {$this->project->title}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.inactivity-warning');
    }
}
