<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Public landing page. Every number shown here is counted from the real
     * database — nothing on this page is invented.
     */
    public function index(Request $request)
    {
        $lectures = Lecture::query()
            ->where('status', 'published')
            ->orderByDesc('id')
            ->limit(6)
            ->get(['id', 'title', 'description', 'category', 'duration_minutes', 'level']);

        $stats = [
            'lectures' => Lecture::where('status', 'published')->count(),
            'students' => Student::count(),
            'quizzes'  => Quiz::count(),
            'subjects' => Lecture::where('status', 'published')
                ->whereNotNull('category')
                ->distinct()
                ->count('category'),
        ];

        return view('landing', [
            'lectures'    => $lectures,
            'stats'       => $stats,
            'heroLecture' => $lectures->first(),
        ]);
    }
}
