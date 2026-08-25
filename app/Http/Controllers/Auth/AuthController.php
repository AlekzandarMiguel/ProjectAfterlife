<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Mail\RegistrationReceivedMail;
use App\Services\AuditService;
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

        return redirect()->route('register.pending')->with([
            'registered_email' => $user->email,
            'registered_name' => $user->name,
        ]);
    }

        public function showRegisterPending(): View
    {
        return view('auth.register-pending');
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

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->input('email')],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->input('email')]);

        return back()->with('status', 'A password reset token has been generated.')->with('reset_url', $resetUrl);
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

        $record = DB::table('password_reset_tokens')->where('email', $request->input('email'))->first();

        if (!$record || !Hash::check($request->input('token'), $record->token)) {
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired. Please request a new one.']);
        }

        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);

            DB::table('password_reset_tokens')->where('email', $request->input('email'))->delete();
            AuditService::log('PASSWORD_RESET_COMPLETED', $user);
        }

        return redirect()->route('login')->with('success', 'Your password has been successfully reset. You can now sign in with your new credentials.');
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
