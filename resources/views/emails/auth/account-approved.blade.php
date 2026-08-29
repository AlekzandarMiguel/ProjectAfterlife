<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved — Project Afterlife</title>
</head>
<body style="margin: 0; padding: 0; background-color: #020617; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #020617; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); padding: 32px; border-bottom: 1px solid #1e293b; text-align: center;">
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
                            <div style="display: inline-block; padding: 4px 12px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 9999px; font-size: 11px; font-weight: 600; color: #34d399; font-family: monospace; margin-bottom: 16px;">
                                🎉 ACCESS ACTIVATED
                            </div>

                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #ffffff;">
                                Congratulations {{ $user->name }},
                            </h2>

                            <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 24px; color: #94a3b8;">
                                Great news! Your developer account on <strong style="color: #ffffff;">Project Afterlife</strong> has been verified and approved by our administrative team.
                            </p>

                            <div style="background-color: #020617; border: 1px solid #1e293b; border-radius: 12px; padding: 20px; margin: 24px 0;">
                                <h3 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #34d399;">
                                    What you can do now:
                                </h3>
                                <ul style="margin: 0; padding-left: 20px; font-size: 13px; line-height: 22px; color: #cbd5e1;">
                                    <li>Adopt abandoned software projects and lead their modern recovery.</li>
                                    <li>Submit your own archived projects for community adoption.</li>
                                    <li>Access private recovery workspaces, tasks, and file archives.</li>
                                </ul>
                            </div>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 28px 0 16px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('login') }}" style="display: inline-block; padding: 14px 32px; background-color: #059669; color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 700; box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.4);">
                                            Sign In to Your Account &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #020617; padding: 24px 32px; border-top: 1px solid #1e293b; text-align: center; font-size: 11px; color: #475569;">
                            Project Afterlife &bull; Bringing Abandoned Software Back to Life
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>