<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;

class AdminActivityController extends Controller
{
    public function index()
    {
        $logs = AdminActivityLog::with('user')
            ->latest()
            ->paginate(30);

        return view('admin.activity.index', compact('logs'));
    }
}
