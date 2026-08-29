<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code — Project Afterlife</title>
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
                                Secure Account Recovery
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 36px 32px; text-align: center;">
                            <div style="display: inline-block; padding: 4px 12px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 9999px; font-size: 11px; font-weight: 600; color: #34d399; font-family: monospace; margin-bottom: 16px;">
                                🔒 VERIFICATION CODE
                            </div>

                            <h2 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 700; color: #ffffff;">
                                Hello {{ $user->name }},
                            </h2>

                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 24px; color: #94a3b8; max-width: 440px; margin-left: auto; margin-right: auto;">
                                We received a request to reset the password for your Project Afterlife account. Use the 6-digit verification code below to proceed:
                            </p>

                            <!-- 6-Digit Code Box -->
                            <div style="background-color: #020617; border: 2px dashed #059669; border-radius: 16px; padding: 24px 16px; margin: 24px auto; max-width: 320px;">
                                <div style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #34d399; font-family: 'Courier New', Courier, monospace;">
                                    {{ $otp }}
                                </div>
                                <div style="font-size: 11px; color: #64748b; font-family: monospace; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                    ⏱️ Expires in 15 minutes
                                </div>
                            </div>

                            <p style="margin: 24px 0 0 0; font-size: 12px; line-height: 18px; color: #64748b;">
                                If you did not request a password reset, you can safely ignore this email. Your account remains completely secure.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #020617; padding: 20px 32px; border-top: 1px solid #1e293b; text-align: center; font-size: 11px; color: #475569;">
                            Project Afterlife &bull; Security & Account Protection System
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>