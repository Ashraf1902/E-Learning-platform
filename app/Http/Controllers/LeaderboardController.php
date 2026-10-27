<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\QuizAttempt;
use App\Models\Student;

class LeaderboardController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->user()->isAdmin();
    }

    private function routeName(string $name): string
    {
        return $this->isAdmin() ? $name : 'portal.' . $name;
    }

    public function index()
    {
        $query = Lecture::where('status', 'published')
            ->withCount('quizzes')
            ->whereHas('quizzes');

        // Students only see lectures whose leaderboard the admin enabled.
        if (!$this->isAdmin()) {
            $query->where('show_leaderboard', true);
        }

        $lectures = $query->get();

        return view('leaderboard.index', compact('lectures'));
    }

    public function show(Lecture $lecture)
    {
        // Students get a 404 for leaderboards the admin has hidden.
        if (!$this->isAdmin() && !$lecture->show_leaderboard) {
            abort(404);
        }

        $lecture->load('quizzes.questions');

        $quizIds = $lecture->quizzes->pluck('id');

        $leaderboard = Student::whereHas('quizAttempts', function ($q) use ($quizIds) {
                $q->whereIn('quiz_id', $quizIds);
            })
            ->with(['quizAttempts' => function ($q) use ($quizIds) {
                $q->whereIn('quiz_id', $quizIds);
            }])
            ->get()
            ->map(function ($student) use ($lecture) {
                $attempts = $student->quizAttempts;

                $bestPerQuiz = $attempts->groupBy('quiz_id')->map(function ($group) {
                    return $group->sortByDesc('score')->first();
                });

                $totalScore     = $bestPerQuiz->sum('score');
                $totalPoints    = $bestPerQuiz->sum('total_points');
                $quizzesPassed  = $bestPerQuiz->where('passed', true)->count();
                $percentage     = $totalPoints > 0 ? round(($totalScore / $totalPoints) * 100) : 0;

                return [
                    'student'        => $student,
                    'total_score'    => $totalScore,
                    'total_points'   => $totalPoints,
                    'percentage'     => $percentage,
                    'quizzes_passed' => $quizzesPassed,
                    'attempts_count' => $attempts->count(),
                ];
            })
            ->sortByDesc('percentage')
            ->values();

        // Per-lecture cap chosen by the admin (students only — the admin
        // always sees the full ranking while managing it).
        if (!$this->isAdmin() && $lecture->leaderboard_limit) {
            $leaderboard = $leaderboard->take($lecture->leaderboard_limit)->values();
        }

        return view('leaderboard.show', compact('lecture', 'leaderboard'));
    }
}
