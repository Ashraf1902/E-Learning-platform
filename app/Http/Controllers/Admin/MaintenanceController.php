<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    public function index()
    {
        $down = File::exists(storage_path('framework/down'));
        $secret = null;

        if ($down) {
            $payload = json_decode((string) File::get(storage_path('framework/down')), true);
            $secret = $payload['secret'] ?? null;
        }

        return view('admin.maintenance.index', [
            'down' => $down,
            'secretUrl' => $secret ? url($secret) : null,
            'email' => null,
        ]);
    }

    public function down(Request $request): RedirectResponse
    {
        $request->validate([
            'secret' => ['nullable', 'string', 'alpha_dash', 'max:64'],
        ]);

        $options = ['--render' => 'errors.503'];

        if ($secret = $request->input('secret')) {
            $options['--secret'] = $secret;
        }

        try {
            Artisan::call('down', $options);
            Log::info('Maintenance mode enabled by admin', ['user_id' => auth()->id()]);

            return redirect()->route('admin.maintenance.index')->with('status', __('app.maintenance_enabled'));
        } catch (\Throwable $e) {
            Log::error('Failed to enable maintenance mode', ['error' => $e->getMessage()]);

            return redirect()->route('admin.maintenance.index')->with('error', $e->getMessage());
        }
    }

    public function up(): RedirectResponse
    {
        try {
            Artisan::call('up');
            Log::info('Maintenance mode disabled by admin', ['user_id' => auth()->id()]);

            return redirect()->route('admin.maintenance.index')->with('status', __('app.maintenance_disabled'));
        } catch (\Throwable $e) {
            Log::error('Failed to disable maintenance mode', ['error' => $e->getMessage()]);

            return redirect()->route('admin.maintenance.index')->with('error', $e->getMessage());
        }
    }
}
