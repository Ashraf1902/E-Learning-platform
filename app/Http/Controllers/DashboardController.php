<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\Quiz;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'lectures'  => Lecture::count(),
            'students'  => Student::count(),
            'quizzes'   => Quiz::count(),
            'published' => Lecture::where('status', 'published')->count(),
        ];

        $recentLectures = Lecture::latest()->take(5)->get();
        $recentStudents = Student::withCount('lectures')->latest()->take(5)->get();
        $recentQuizzes  = Quiz::with('lecture')->withCount('questions')->latest()->take(5)->get();

        $levels = [
            'first'  => ['label' => 'الصف الأول بكالوريا',   'count' => Student::where('level', 'first')->count()],
            'second' => ['label' => 'الصف الثاني بكالوريا',  'count' => Student::where('level', 'second')->count()],
            'third'  => ['label' => 'الصف الثالث الاعدادي', 'count' => Student::where('level', 'third')->count()],
        ];

        return view('dashboard', compact('stats', 'recentLectures', 'recentStudents', 'recentQuizzes', 'levels'));
    }
}
