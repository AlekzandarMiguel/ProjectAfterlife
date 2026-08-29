<?php

use App\Http\Controllers\Admin\AdoptionReviewController as AdminAdoptionReviewController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FinalReviewController as AdminFinalReviewController;
use App\Http\Controllers\Admin\OwnershipTransferController as AdminOwnershipTransferController;
use App\Http\Controllers\Admin\ProjectManagementController as AdminProjectManagementController;
use App\Http\Controllers\Admin\ProjectReviewController as AdminProjectReviewController;
use App\Http\Controllers\Admin\RecoveryMonitorController as AdminRecoveryMonitorController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\TaxonomyController as AdminTaxonomyController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResurrectedController;
use App\Http\Controllers\User\AdoptionController as UserAdoptionController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\FinalReviewController as UserFinalReviewController;
use App\Http\Controllers\User\MyProjectsController as UserMyProjectsController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\ProjectSubmissionController as UserProjectSubmissionController;
use App\Http\Controllers\User\ProjectVersionController as UserProjectVersionController;
use App\Http\Controllers\User\RecoveryWorkspaceController as UserRecoveryWorkspaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');
Route::get('/explore/{project:slug}', [ExploreController::class, 'show'])->name('explore.show');
Route::get('/explore/{project:slug}/files/{file}', [ExploreController::class, 'downloadFile'])->name('explore.files.download')->middleware('auth');
Route::get('/resurrected', [ResurrectedController::class, 'index'])->name('resurrected.index');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:15,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:15,1');
    Route::get('/register/pending', [AuthController::class, 'showRegisterPending'])->name('register.pending');
    Route::get('/register/check-status', [AuthController::class, 'checkRegistrationStatus'])->name('register.check-status');

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:15,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:15,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('app')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Multi-Step Project Upload & Management
    Route::get('/projects/upload', [UserProjectSubmissionController::class, 'create'])->name('projects.create');
    Route::post('/projects/upload', [UserProjectSubmissionController::class, 'store'])->name('projects.store');
    Route::get('/my-projects', [UserMyProjectsController::class, 'index'])->name('projects.index');
    Route::get('/my-projects/{project:slug}', [UserMyProjectsController::class, 'show'])->name('projects.show');
    Route::get('/my-projects/{project:slug}/edit', [UserProjectSubmissionController::class, 'edit'])->name('projects.edit');
    Route::put('/my-projects/{project:slug}', [UserProjectSubmissionController::class, 'update'])->name('projects.update');

    // Adoption Workflow
    Route::get('/adoptions', [UserAdoptionController::class, 'index'])->name('adoptions.index');
    Route::get('/projects/{project:slug}/adopt', [UserAdoptionController::class, 'create'])->name('adoptions.create');
    Route::post('/projects/{project:slug}/adopt', [UserAdoptionController::class, 'store'])->name('adoptions.store');
    Route::get('/adoptions/{adoptionRequest}', [UserAdoptionController::class, 'show'])->name('adoptions.show');

    // Recovery Workspace & Task Engine
    Route::get('/recovery', [UserRecoveryWorkspaceController::class, 'index'])->name('recovery.index');
    Route::get('/recovery/{project:slug}', [UserRecoveryWorkspaceController::class, 'workspace'])->name('recovery.workspace');
    Route::post('/recovery/{project:slug}/tasks', [UserRecoveryWorkspaceController::class, 'storeTask'])->name('recovery.tasks.store');
    Route::patch('/recovery/{project:slug}/tasks/{task}', [UserRecoveryWorkspaceController::class, 'updateTaskStatus'])->name('recovery.tasks.update');
    Route::post('/recovery/{project:slug}/updates', [UserRecoveryWorkspaceController::class, 'storeUpdate'])->name('recovery.updates.store');

    // Version Management
    Route::get('/recovery/{project:slug}/versions', [UserProjectVersionController::class, 'index'])->name('versions.index');
    Route::post('/recovery/{project:slug}/versions', [UserProjectVersionController::class, 'store'])->name('versions.store');

    // Final Review & Resurrection Request
    Route::get('/recovery/{project:slug}/final-review', [UserFinalReviewController::class, 'create'])->name('final-review.create');
    Route::post('/recovery/{project:slug}/final-review', [UserFinalReviewController::class, 'store'])->name('final-review.store');

    // Profile & Notifications
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/settings', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/settings/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Administrator Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');

    // Project Submissions & Review
    Route::get('/submissions', [AdminProjectReviewController::class, 'index'])->name('projects.submissions.index');
    Route::get('/submissions/{project:slug}', [AdminProjectReviewController::class, 'show'])->name('projects.submissions.show');
    Route::post('/submissions/{project:slug}/approve', [AdminProjectReviewController::class, 'approve'])->name('projects.submissions.approve');
    Route::post('/submissions/{project:slug}/reject', [AdminProjectReviewController::class, 'reject'])->name('projects.submissions.reject');
    Route::post('/submissions/{project:slug}/revision', [AdminProjectReviewController::class, 'requestRevision'])->name('projects.submissions.revision');

    // All Projects Management
    Route::get('/projects', [AdminProjectManagementController::class, 'index'])->name('projects.index');
    Route::patch('/projects/{project:slug}/toggle-featured', [AdminProjectManagementController::class, 'toggleFeatured'])->name('projects.toggle-featured');

    // Adoption Requests Review & Ownership Transfer
    Route::get('/adoption-requests', [AdminAdoptionReviewController::class, 'index'])->name('adoption-requests.index');
    Route::get('/adoption-requests/{adoptionRequest}', [AdminAdoptionReviewController::class, 'show'])->name('adoption-requests.show');
    Route::post('/adoption-requests/{adoptionRequest}/approve', [AdminAdoptionReviewController::class, 'approve'])->name('adoption-requests.approve');
    Route::post('/adoption-requests/{adoptionRequest}/reject', [AdminAdoptionReviewController::class, 'reject'])->name('adoption-requests.reject');

    // Ownership Transfers Immutable Ledger
    Route::get('/ownership-transfers', [AdminOwnershipTransferController::class, 'index'])->name('ownership-transfers.index');

    // Recovery Monitoring & Inactivity
    Route::get('/recovery-monitoring', [AdminRecoveryMonitorController::class, 'index'])->name('recovery.index');
    Route::post('/recovery-monitoring/{project:slug}/warning', [AdminRecoveryMonitorController::class, 'sendWarning'])->name('recovery.warning');
    Route::post('/recovery-monitoring/{project:slug}/inactive', [AdminRecoveryMonitorController::class, 'markInactive'])->name('recovery.inactive');
    Route::post('/recovery-monitoring/{project:slug}/reabandon', [AdminRecoveryMonitorController::class, 'markAbandonedAgain'])->name('recovery.reabandon');
    Route::post('/recovery-monitoring/{project:slug}/reopen', [AdminRecoveryMonitorController::class, 'reopenForAdoption'])->name('recovery.reopen');

    // Final Reviews & Resurrection Certification
    Route::get('/final-reviews', [AdminFinalReviewController::class, 'index'])->name('final-reviews.index');
    Route::get('/final-reviews/{finalReview}', [AdminFinalReviewController::class, 'show'])->name('final-reviews.show');
    Route::post('/final-reviews/{finalReview}/approve', [AdminFinalReviewController::class, 'approve'])->name('final-reviews.approve');
    Route::post('/final-reviews/{finalReview}/revision', [AdminFinalReviewController::class, 'requestRevision'])->name('final-reviews.revision');

    // Taxonomies (Categories & Tech)
    Route::get('/categories', [AdminTaxonomyController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminTaxonomyController::class, 'storeCategory'])->name('categories.store');
    Route::get('/technologies', [AdminTaxonomyController::class, 'technologies'])->name('technologies.index');
    Route::post('/technologies', [AdminTaxonomyController::class, 'storeTechnology'])->name('technologies.store');

    // Audit Logs & Settings
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});
