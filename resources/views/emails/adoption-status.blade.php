<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 24px; }
        .card { max-width: 560px; margin: 0 auto; background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 32px; }
        .logo { font-size: 16px; font-weight: 800; color: #10b981; font-family: monospace; letter-spacing: 1px; }
        h1 { font-size: 20px; font-weight: 700; color: #ffffff; margin-top: 16px; }
        p { font-size: 14px; line-height: 1.6; color: #cbd5e1; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; font-family: monospace; }
        .badge-approved { background: #064e3b; color: #6ee7b7; border: 1px solid #059669; }
        .badge-declined { background: #4c0519; color: #fda4af; border: 1px solid #e11d48; }
        .btn { display: inline-block; padding: 10px 20px; background: #10b981; color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 700; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 11px; color: #64748b; font-family: monospace; border-top: 1px solid #334155; pt: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">PROJECT AFTERLIFE</div>
        <h1>Adoption Proposal Update</h1>

        <p>Hello <strong>{{ $adoptionRequest->applicant->name }}</strong>,</p>

        @if($status === 'approved')
            <p>Your adoption proposal for <strong>{{ $adoptionRequest->project->title }}</strong> has been officially approved by platform administrators.</p>
            <span class="badge badge-approved">STEWARDSHIP TRANSFERRED</span>
            <p>The repository is now in <strong>Under Recovery</strong> status under your stewardship. You can access the dedicated Recovery Workspace to manage tasks, publish version tags, and communicate updates.</p>
            <a href="{{ route('user.recovery.workspace', $adoptionRequest->project) }}" class="btn">Open Recovery Workspace &rarr;</a>
        @else
            <p>Your adoption request for <strong>{{ $adoptionRequest->project->title }}</strong> was reviewed and declined by platform administrators.</p>
            <span class="badge badge-declined">PROPOSAL DECLINED</span>
            @if($feedback)
                <p><strong>Administrator Feedback:</strong><br><em>"{{ $feedback }}"</em></p>
            @endif
            <a href="{{ route('explore.index') }}" class="btn">Browse Other Projects &rarr;</a>
        @endif

        <div class="footer">
            Project Afterlife Software Preservation Registry &bull; Automated Governance Dispatch
        </div>
    </div>
</body>
</html>
