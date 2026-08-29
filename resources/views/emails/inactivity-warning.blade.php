<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 24px; }
        .card { max-width: 560px; margin: 0 auto; background: #1e293b; border: 1px solid #eab308; border-radius: 16px; padding: 32px; }
        .logo { font-size: 16px; font-weight: 800; color: #eab308; font-family: monospace; letter-spacing: 1px; }
        h1 { font-size: 20px; font-weight: 700; color: #ffffff; margin-top: 16px; }
        p { font-size: 14px; line-height: 1.6; color: #cbd5e1; }
        .btn { display: inline-block; padding: 10px 20px; background: #eab308; color: #000000; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 700; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 11px; color: #64748b; font-family: monospace; border-top: 1px solid #334155; pt: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">PROJECT AFTERLIFE &bull; RECOVERY MONITOR</div>
        <h1>Stewardship Inactivity Notice</h1>

        <p>Hello <strong>{{ $project->owner->name }}</strong>,</p>
        <p>Our automated preservation monitor detected no recorded progress or task completions on <strong>{{ $project->title }}</strong> in the last <strong>{{ $daysInactive }} days</strong>.</p>
        <p>To keep your custody active and prevent the repository from being automatically returned to the open adoption registry, please log in and submit a recovery progress update.</p>

        <a href="{{ route('user.recovery.workspace', $project) }}" class="btn">Submit Progress Update &rarr;</a>

        <div class="footer">
            Project Afterlife Automated Inactivity Daemon &bull; Governance System
        </div>
    </div>
</body>
</html>
