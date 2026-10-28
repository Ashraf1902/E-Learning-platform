<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Support\DatabaseBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DatabaseBackupController extends Controller
{
    public function download(Request $request)
    {
        @set_time_limit(0);

        $filename = 'el-molhem-db-' . now()->format('Y-m-d-His') . '.sql.gz';
        $directory = storage_path('app/backups');
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        File::ensureDirectoryExists($directory, 0755, true);

        try {
            app(DatabaseBackup::class)->writeTo($path);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', app()->getLocale() === 'ar'
                ? 'تعذر إنشاء نسخة قاعدة البيانات. حاول مرة أخرى.'
                : 'Unable to create the database backup. Please try again.');
        }

        try {
            AdminActivityLog::recordFromRequest($request, 200);
        } catch (\Throwable $e) {
            report($e);
        }

        return response()
            ->download($path, $filename, ['Content-Type' => 'application/gzip'])
            ->deleteFileAfterSend(true);
    }
}
