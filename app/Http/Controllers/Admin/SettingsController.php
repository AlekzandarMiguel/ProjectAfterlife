<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = [
            'inactivity_threshold_days' => config('afterlife.inactivity_threshold_days', 30),
            'max_upload_size_mb' => config('afterlife.max_upload_size_mb', 50),
            'allow_public_registration' => config('afterlife.allow_public_registration', true),
            'app_name' => config('app.name', 'Project Afterlife'),
            'app_env' => config('app.env', 'production'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_driver' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'google_oauth_active' => !empty(config('services.google.client_id')),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inactivity_threshold_days' => ['required', 'integer', 'min:7', 'max:180'],
            'max_upload_size_mb' => ['required', 'integer', 'min:10', 'max:200'],
        ]);

        AuditService::log('SYSTEM_SETTINGS_UPDATED', null, $validated);

        return back()->with('success', 'Platform settings and governance thresholds saved successfully.');
    }
}
