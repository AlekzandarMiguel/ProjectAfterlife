<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 24px; }
        .card { max-width: 560px; margin: 0 auto; background: #1e293b; border: 2px solid #a855f7; border-radius: 16px; padding: 32px; }
        .logo { font-size: 16px; font-weight: 800; color: #c084fc; font-family: monospace; letter-spacing: 1px; }
        h1 { font-size: 22px; font-weight: 700; color: #ffffff; margin-top: 16px; }
        p { font-size: 14px; line-height: 1.6; color: #cbd5e1; }
        .btn { display: inline-block; padding: 10px 20px; background: #9333ea; color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 700; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 11px; color: #64748b; font-family: monospace; border-top: 1px solid #334155; pt: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">PROJECT AFTERLIFE &bull; HALL OF FAME</div>
        <h1>Congratulations! Software Resurrected</h1>

        <p>Hello <strong>{{ $project->owner->name }}</strong>,</p>
        <p>Following final administrative review, your recovery work on <strong>{{ $project->title }}</strong> has been officially certified and inducted into the <strong>Resurrected Hall of Fame</strong>!</p>
        
        <p><strong>Certification Statement:</strong><br><em>"{{ $summary }}"</em></p>

        <a href="{{ route('resurrected.index') }}" class="btn">View in Hall of Fame &rarr;</a>

        <div class="footer">
            Project Afterlife Software Preservation Registry &bull; Official Induction
        </div>
    </div>
</body>
</html>
