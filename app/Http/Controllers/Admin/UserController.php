<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Mail\AccountApprovedMail;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with(['profile', 'ownedProjects', 'uploadedProjects']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pendingCount = User::where('status', UserStatus::PENDING)->count();
        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'pendingCount'));
    }

    public function create(): View
    {
        $roles = UserRole::cases();
        $statuses = UserStatus::cases();
        return view('admin.users.create', compact('roles', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
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
            'role' => ['required', new Enum(UserRole::class)],
            'status' => ['required', new Enum(UserStatus::class)],
            'bio' => ['nullable', 'string', 'max:500'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'username' => strtolower(trim($validated['username'])),
            'email' => strtolower(trim($validated['email'])),
            'password' => $validated['password'], // User model automatically casts hashed password
            'role' => UserRole::from($validated['role']),
            'status' => UserStatus::from($validated['status']),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'bio' => $validated['bio'] ?? ($user->isAdmin() ? 'Project Afterlife System Administrator' : 'Software developer and recovery enthusiast.'),
            'years_of_experience' => $validated['years_of_experience'] ?? ($user->isAdmin() ? 5 : 1),
            'skills' => [],
        ]);

        AuditService::log('ADMIN_PROVISIONED_USER', $user, [
            'created_by' => auth()->id(),
            'assigned_role' => $user->role->value,
            'initial_status' => $user->status->value,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Account for '{$user->name}' ({$user->role->label()}) has been successfully created.");
    }

    public function show(User $user): View
    {
        $user->load(['profile', 'ownedProjects.category', 'uploadedProjects.category', 'adoptionRequests.project', 'ownershipTransfersReceived.project', 'auditLogs' => fn($q) => $q->latest()->take(20)]);
        return view('admin.users.show', compact('user'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update(['status' => UserStatus::ACTIVE]);

        NotificationService::send(
            $user,
            'account_approved',
            '🎉 Account Approved!',
            'Your developer account has been approved by the administrator. You now have full access to Project Afterlife.',
            route('user.dashboard')
        );

        try {
            Mail::to($user->email)->send(new AccountApprovedMail($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Failed sending account approval email to {$user->email}: " . $e->getMessage());
        }

        AuditService::log('USER_APPROVED_BY_ADMIN', $user, ['approved_by' => auth()->id()]);

        return back()->with('success', "User {$user->name} has been approved and activated.");
    }

    public function reject(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot reject your own administrative account.');
        }

        $user->update(['status' => UserStatus::SUSPENDED]);

        AuditService::log('USER_REJECTED_BY_ADMIN', $user, ['rejected_by' => auth()->id()]);

        return back()->with('success', "User {$user->name} registration has been rejected and suspended.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own administrative account.');
        }

        $newStatus = $user->status === UserStatus::ACTIVE ? UserStatus::SUSPENDED : UserStatus::ACTIVE;
        $user->update(['status' => $newStatus]);

        AuditService::log('USER_STATUS_TOGGLED', $user, ['new_status' => $newStatus->value]);

        return back()->with('success', "User {$user->name} status changed to {$newStatus->value}.");
    }
    public function promote(Request $request, User $user): RedirectResponse
    {
        $admin = auth()->user();

        if ($user->id === $admin->id) {
            return back()->with('error', 'Security Violation: You cannot alter your own administrative role.');
        }

        if (!$user->isActive()) {
            return back()->with('error', 'Cannot change role for inactive or suspended users.');
        }

        $validated = $request->validate([
            'role' => ['required', new Enum(UserRole::class)],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
            'admin_password' => ['required', 'string'],
        ], [
            'reason.required' => 'A formal security justification is required for role elevation.',
            'reason.min' => 'Security justification must be at least 10 characters.',
            'admin_password.required' => 'Please confirm your administrator password to authorize this action.',
        ]);

        // Sudo-mode security verification: authenticate acting admin's password
        if (!Hash::check($validated['admin_password'], $admin->password)) {
            return back()->withErrors(['admin_password' => 'Security verification failed: Incorrect administrator password.'])->withInput();
        }

        $newRole = UserRole::from($validated['role']);
        $oldRole = $user->role;

        if ($oldRole === $newRole) {
            return back()->with('info', "User '{$user->name}' is already assigned the {$newRole->label()} role.");
        }

        $user->update(['role' => $newRole]);

        AuditService::log('ADMIN_USER_ROLE_ELEVATION', $user, [
            'authorized_by_admin_id' => $admin->id,
            'authorized_by_admin_name' => $admin->name,
            'previous_role' => $oldRole->value,
            'elevated_role' => $newRole->value,
            'justification' => $validated['reason'],
            'ip_address' => $request->ip(),
        ]);

        NotificationService::send(
            $user,
            'role_updated',
            'Account Role Elevation',
            "Your account privileges have been updated to {$newRole->label()} by Administrator {$admin->name}. Justification: {$validated['reason']}",
            $newRole === UserRole::ADMIN ? route('admin.dashboard') : route('user.dashboard')
        );

        return back()->with('success', "Security Verified: User '{$user->name}' has been successfully elevated to {$newRole->label()}.");
    }
}
