<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Lecture;
use App\Models\QuizAttempt;
use App\Support\AnswerChecker;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    private function getStudent()
    {
        return auth()->user()->student;
    }

    public function index()
    {
        $student        = $this->getStudent();
        $lectures       = $student->lectures()->count();
        $attempts       = $student->quizAttempts()->count();
        $passed         = $student->quizAttempts()->where('passed', true)->count();
        $recentLectures = $student->lectures()->latest()->take(4)->get();
        $recentAttempts = $student->quizAttempts()->with('quiz')->latest()->take(4)->get();

        return view('portal.dashboard', compact(
            'student', 'lectures', 'attempts', 'passed', 'recentLectures', 'recentAttempts'
        ));
    }

    public function lectures()
    {
        $student = $this->getStudent();

        // Subscribers see everything they're enrolled in; code-only students
        // see exactly the lectures their codes unlocked.
        if ($student->hasActiveSubscription()) {
            $lectures = $student->lectures()->paginate(10);
        } else {
            $lectures = Lecture::whereIn('id', $student->unlockedLectureIds())
                ->latest()
                ->paginate(10);
        }

        return view('portal.lectures', compact('lectures'));
    }

    public function showLecture(Lecture $lecture)
    {
        $student = $this->getStudent();

        if ($student->hasActiveSubscription()) {
            if (!$student->lectures->contains($lecture->id)) {
                abort(403, 'غير مسجل في هذه المحاضرة.');
            }
        } elseif (!$student->hasUnlockedLecture($lecture)) {
            abort(403, 'هذه المحاضرة غير مفتوحة لك. استخدم كود الفتح أو فعّل الاشتراك.');
        }

        $lecture->load('quizzes');
        $student->load('quizAttempts');
        return view('portal.lecture-show', compact('lecture', 'student'));
    }

    public function quizzes()
    {
        $student = $this->getStudent();

        if ($student->hasActiveSubscription()) {
            $lectureIds = $student->lectures->pluck('id');

            $quizzes = Quiz::where('status', 'published')
                ->where(function ($q) use ($lectureIds) {
                    $q->whereIn('lecture_id', $lectureIds)->orWhereNull('lecture_id');
                })
                ->withCount('questions')
                ->with('lecture')
                ->paginate(10);
        } else {
            // No subscription: only quizzes of code-unlocked lectures.
            $quizzes = Quiz::where('status', 'published')
                ->whereIn('lecture_id', $student->unlockedLectureIds())
                ->withCount('questions')
                ->with('lecture')
                ->paginate(10);
        }

        $student->load('quizAttempts');

        return view('portal.quizzes', compact('quizzes', 'student'));
    }

    public function takeQuiz(Quiz $quiz)
    {
        $student = $this->getStudent();
        $this->authorizeQuizAccess($student, $quiz);

        $existing = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            return redirect()->route('portal.results.review', $existing->id)
                ->with('info', 'لقد أجريت هذا الاختبار بالفعل. إليك نتيجتك.');
        }

        $quiz->load('questions');
        return view('portal.quiz-take', compact('quiz', 'student'));
    }
    private function authorizeQuizAccess($student, Quiz $quiz): void
    {
        if ($student->hasActiveSubscription()) {
            return;
        }

        if ($quiz->lecture_id && $student->hasUnlockedLecture($quiz->lecture)) {
            return;
        }

        abort(403, 'هذا الاختبار غير متاح لك حاليًا.');
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $request->validate(['answers' => 'required|array']);

        $student = $this->getStudent();
        $this->authorizeQuizAccess($student, $quiz);
        $quiz->load('questions');
        $answers = $request->answers ?? [];

        [$score, $total, $needsGrading] = \App\Http\Controllers\QuizController::gradeAnswers($quiz, $answers);

        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
        $passed     = !$needsGrading && $percentage >= $quiz->pass_score;

        $attempt = QuizAttempt::create([
            'quiz_id'       => $quiz->id,
            'student_id'    => $student->id,
            'answers'       => $answers,
            'score'         => $score,
            'total_points'  => $total,
            'passed'        => $passed,
            'needs_grading' => $needsGrading,
            'completed_at'  => now(),
        ]);

        return redirect()->route('portal.quizzes.results', [
            'quiz'    => $quiz->id,
            'attempt' => $attempt->id,
        ]);
    }

    public function results(Quiz $quiz, QuizAttempt $attempt)
    {
        $this->authorizeQuizAccess($this->getStudent(), $quiz);

        if ($attempt->quiz_id !== $quiz->id || $attempt->student_id !== $this->getStudent()->id) {
            abort(403, 'ليس لديك صلاحية الوصول.');
        }

        $quiz->load('questions');
        $attempt->load('student');
        return view('portal.quiz-results', compact('quiz', 'attempt'));
    }

    public function myResults()
    {
        $student  = $this->getStudent();
        $attempts = $student->quizAttempts()->with('quiz')->latest()->paginate(10);
        return view('portal.results', compact('attempts', 'student'));
    }

    public function reviewAttempt($attemptId)
    {
        $student = $this->getStudent();
        $attempt = QuizAttempt::with(['quiz.questions', 'quiz.lecture', 'student'])->findOrFail($attemptId);

        if ($attempt->student_id !== $student->id) {
            abort(403, 'ليس لديك صلاحية الوصول.');
        }

        $quiz = $attempt->quiz;
        return view('portal.attempt-review', compact('attempt', 'quiz', 'student'));
    }
}
