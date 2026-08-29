<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Received — Project Afterlife</title>
</head>
<body style="margin: 0; padding: 0; background-color: #020617; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #020617; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #022c22 0%, #0f172a 100%); padding: 32px; border-bottom: 1px solid #1e293b; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                Project Afterlife
                            </h1>
                            <p style="margin: 4px 0 0 0; font-size: 11px; font-family: monospace; text-transform: uppercase; color: #34d399; letter-spacing: 1px;">
                                Software Revival & Preservation Platform
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <div style="display: inline-block; padding: 4px 12px; background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 9999px; font-size: 11px; font-weight: 600; color: #fbbf24; font-family: monospace; margin-bottom: 16px;">
                                ⏳ VERIFICATION IN PROGRESS
                            </div>

                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #ffffff;">
                                Hello {{ $user->name }},
                            </h2>

                            <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 24px; color: #94a3b8;">
                                Thank you for registering on <strong style="color: #ffffff;">Project Afterlife</strong>! Your account application has been received and is currently undergoing Administrator verification.
                            </p>

                            <div style="background-color: #020617; border: 1px solid #1e293b; border-radius: 12px; padding: 20px; margin: 24px 0;">
                                <h3 style="margin: 0 0 12px 0; font-size: 13px; font-weight: 600; color: #e2e8f0; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Why is verification required?
                                </h3>
                                <p style="margin: 0; font-size: 13px; line-height: 20px; color: #64748b;">
                                    To protect open-source repositories from spam, automated tampering, and malicious payloads, our administration team verifies all developer accounts prior to granting full access.
                                </p>
                            </div>

                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 24px; color: #94a3b8;">
                                You will receive an immediate confirmation email as soon as your account is reviewed and approved.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('explore.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #1e293b; color: #e2e8f0; text-decoration: none; border-radius: 10px; font-size: 13px; font-weight: 600; border: 1px solid #334155;">
                                            Browse Public Projects &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #020617; padding: 24px 32px; border-top: 1px solid #1e293b; text-align: center; font-size: 11px; color: #475569;">
                            This is an automated notification from Project Afterlife. Please do not reply directly to this email.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>