<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Public landing page.
     */
    public function index(Request $request)
    {
        $lectures = Lecture::query()
            ->where('status', 'published')
            ->orderByDesc('id')
            ->limit(6)
            ->get(['id', 'title', 'description', 'category', 'duration_minutes', 'level']);

        return view('landing', [
            'lectures'    => $lectures,
            'heroLecture' => $lectures->first(),
        ]);
    }
}
