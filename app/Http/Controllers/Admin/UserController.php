<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
