<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Mail\PasswordResetOtpMail;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\RegistrationReceivedMail;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function redirectToGoogle(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google authentication failed or was cancelled. Please try again.']);
        }

        $googleId = (string) $googleUser->getId();
        $email = strtolower(trim((string) $googleUser->getEmail()));
        $name = (string) ($googleUser->getName() ?? 'Google User');
        $avatar = $googleUser->getAvatar();

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleId,
                    'auth_provider' => 'google',
                    'avatar' => $avatar ?? $user->avatar,
                ]);
            }

            if ($user->isPending()) {
                return redirect()->route('register.pending', ['email' => $user->email])
                    ->with('status', 'Your account is currently pending administrator verification.');
            }

            if (!$user->isActive()) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been suspended. Please contact the administrator.']);
            }

            Auth::login($user, true);
            AuditService::log('USER_LOGIN_GOOGLE', $user);

            $defaultRoute = $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard');
            return redirect()->intended($defaultRoute);
        }

        // Generate clean unique username from email prefix
        $baseUsername = Str::slug(explode('@', $email)[0], '');
        if (empty($baseUsername)) {
            $baseUsername = 'dev';
        }
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter++;
        }

        // Create new developer account (PENDING admin approval)
        $newUser = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'google_id' => $googleId,
            'auth_provider' => 'google',
            'avatar' => $avatar,
            'password' => Str::random(32),
            'role' => UserRole::USER,
            'status' => UserStatus::PENDING,
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $newUser->id,
            'bio' => 'Software developer registered via Google Authentication.',
            'years_of_experience' => 1,
            'skills' => [],
        ]);

        NotificationService::notifyAdmins(
            'user_registration_pending',
            'New Google User Awaiting Approval',
            "New developer {$newUser->name} ({$newUser->email}) registered with Google and is awaiting administrator verification.",
            route('admin.users.show', $newUser)
        );

        try {
            Mail::to($newUser->email)->send(new RegistrationReceivedMail($newUser));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Failed sending registration email to {$newUser->email}: " . $e->getMessage());
        }

        AuditService::log('USER_REGISTERED_GOOGLE_PENDING_APPROVAL', $newUser);

        return redirect()->route('register.pending', ['email' => $newUser->email])
            ->with([
                'registered_email' => $newUser->email,
                'registered_name' => $newUser->name,
                'status' => 'Google account connected! Your registration is now pending administrator verification.',
            ]);
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'Please enter your registered email address.',
            'email.email' => 'Please enter a valid email address format (e.g. user@domain.com).',
            'email.regex' => 'Please enter a valid email format with a proper domain name.',
            'password.required' => 'Please enter your account password.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isPending()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Your account is currently pending administrator verification. You will be able to sign in once an administrator approves your account.']);
            }

            if (!$user->isActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Your account has been suspended. Please contact the administrator.']);
            }

            AuditService::log('USER_LOGIN', $user);

            $defaultRoute = $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard');
            $intended = session()->get('url.intended');

            if (!$intended || $intended === url('/') || $intended === route('home') || $intended === url('/login') || $intended === route('login')) {
                session()->forget('url.intended');
                return redirect()->to($defaultRoute);
            }

            return redirect()->intended($defaultRoute);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records. Please verify your email and password.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z\s\.\'-]+$/'
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'alpha_dash:ascii',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_-]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
            'password_confirmation' => ['required', 'string'],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Full name must be at least 3 characters.',
            'name.regex' => 'Full name must contain only valid letters, spaces, dots, or hyphens.',
            'username.required' => 'Please choose a username.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes, and underscores.',
            'username.unique' => 'This username is already taken. Please choose another.',
            'email.required' => 'Please enter a valid email address.',
            'email.email' => 'Please enter a valid email format (e.g. jane@domain.com).',
            'email.regex' => 'Please enter a realistic email address with a valid domain.',
            'email.unique' => 'An account with this email already exists. Please sign in or use another email.',
            'password.required' => 'Please enter a secure password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Please confirm your password.',
            'terms.accepted' => 'You must agree to the platform terms and software transfer guidelines.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'username' => strtolower(trim($validated['username'])),
            'email' => strtolower(trim($validated['email'])),
            'password' => $validated['password'],
            'role' => UserRole::USER,
            'status' => UserStatus::PENDING,
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'bio' => 'Software developer and recovery enthusiast.',
            'years_of_experience' => 1,
            'skills' => [],
        ]);

        \App\Services\NotificationService::notifyAdmins(
            'user_registration_pending',
            'New Account Awaiting Approval',
            "New developer {$user->name} ({$user->email}) registered and is awaiting administrator verification.",
            route('admin.users.show', $user)
        );

        try {
            Mail::to($user->email)->send(new RegistrationReceivedMail($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Failed sending registration received email to {$user->email}: " . $e->getMessage());
        }

        AuditService::log('USER_REGISTERED_PENDING_APPROVAL', $user);

        session(['registered_email' => $user->email, 'registered_name' => $user->name]);

        return redirect()->route('register.pending', ['email' => $user->email])->with([
            'registered_email' => $user->email,
            'registered_name' => $user->name,
        ]);
    }

        public function showRegisterPending(Request $request): View
    {
        $email = session('registered_email') ?? $request->query('email');
        $user = $email ? User::where('email', $email)->first() : null;
        $isApproved = $user ? $user->isActive() : false;

        return view('auth.register-pending', compact('user', 'isApproved', 'email'));
    }

    public function checkRegistrationStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $email = session('registered_email') ?? $request->query('email');
        $user = $email ? User::where('email', $email)->first() : null;

        if (!$user) {
            return response()->json(['status' => 'unknown', 'is_approved' => false]);
        }

        return response()->json([
            'status' => $user->status->value,
            'is_approved' => $user->isActive(),
            'is_suspended' => $user->isSuspended(),
            'redirect_url' => route('login'),
        ]);
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email:rfc',
                'exists:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
        ], [
            'email.required' => 'Please enter your registered email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find an account with that email address.',
        ]);

        $email = strtolower(trim($request->input('email')));
        $user = User::where('email', $email)->firstOrFail();

        // Generate cryptographically secure 6-digit OTP
        $otp = sprintf('%06d', random_int(100000, 999999));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($user, $otp));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Failed sending password reset OTP to {$user->email}: " . $e->getMessage());
        }

        return redirect()->route('password.verify.form', ['email' => $email])
            ->with('status', 'A 6-digit verification code has been sent to your email.');
    }

    public function showVerifyCode(Request $request): View
    {
        $email = $request->query('email', '');
        return view('auth.verify-reset-code', compact('email'));
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc', 'exists:users,email'],
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Please enter the 6-digit verification code.',
            'code.size' => 'The verification code must be exactly 6 digits.',
        ]);

        $email = strtolower(trim($request->input('email')));
        $code = trim($request->input('code'));

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($code, $record->token)) {
            return back()->withErrors(['code' => 'The 6-digit verification code is invalid.'])->withInput();
        }

        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['code' => 'This verification code has expired. Please request a new code.'])->withInput();
        }

        // Generate verified reset authorization token
        $verifiedToken = Str::random(64);
        DB::table('password_reset_tokens')->where('email', $email)->update([
            'token' => Hash::make($verifiedToken),
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('password.reset', ['token' => $verifiedToken, 'email' => $email])
            ->with('status', 'Code verified successfully. Please choose your new password.');
    }

    public function showResetPassword(string $token, Request $request): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', '')
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email:rfc', 'exists:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
            'password_confirmation' => ['required', 'string'],
        ], [
            'email.required' => 'Please specify your account email.',
            'email.exists' => 'No account was found matching this email address.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $email = strtolower(trim($request->input('email')));
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($request->input('token'), $record->token)) {
            return back()->withErrors(['email' => 'The password reset session has expired or is invalid. Please request a new code.']);
        }

        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['email' => 'This reset session has expired. Please request a new code.']);
        }

        $user = User::where('email', $email)->firstOrFail();

        $user->forceFill([
            'password' => $request->input('password'),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        AuditService::log('USER_PASSWORD_RESET', $user);

        NotificationService::notifyAdmins(
            'user_password_reset',
            'Security Alert: Password Reset',
            "User {$user->name} ({$user->email}) completed a password reset via email OTP verification.",
            route('admin.users.show', $user)
        );

        return redirect()->route('login')->with('success', 'Your password has been successfully reset. Please sign in with your new password.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            AuditService::log('USER_LOGOUT', $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been successfully logged out.');
    }
}
