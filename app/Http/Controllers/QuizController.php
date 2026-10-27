<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Student;
use App\Models\Lecture;
use App\Models\QuizAttempt;
use App\Support\AnswerChecker;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('lecture')
            ->withCount('questions')
            ->latest()
            ->paginate(10);
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $lectures = Lecture::where('status', 'published')->get();
        return view('quizzes.create', compact('lectures'));
    }

    public function store(Request $request)
    {
        $this->validateQuizForm($request);

        $quiz = Quiz::create($request->only('title', 'description', 'lecture_id', 'pass_score', 'status'));

        foreach (array_values($request->input('questions', [])) as $position => $q) {
            $this->persistQuestion($quiz, $q, $position);
        }

        return redirect()->route('quizzes.index')
            ->with('success', 'تم إنشاء الاختبار بنجاح!');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['questions' => fn ($q) => $q->orderBy('position')->orderBy('id'), 'lecture', 'attempts.student']);
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $lectures = Lecture::where('status', 'published')->get();
        $quiz->load(['questions' => fn ($q) => $q->orderBy('position')->orderBy('id')]);
        return view('quizzes.edit', compact('quiz', 'lectures'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->validateQuizForm($request);

        $quiz->update($request->only('title', 'description', 'lecture_id', 'pass_score', 'status'));
        $this->syncQuestions($request, $quiz);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'تم حفظ الاختبار والأسئلة!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted.');
    }

    // --- Admin grades essay questions on an attempt ---
    public function gradeAttempt(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($attempt->quiz_id === $quiz->id, 404);

        $attempt->loadMissing('quiz.questions');

        $request->validate([
            'scores'         => 'nullable|array',
            'scores.*'       => 'required|integer|min:0',
        ], [
            'scores.*.required' => 'أدخل درجة صحيحة.',
            'scores.*.integer'  => 'الدرجة يجب أن تكون رقماً صحيحاً.',
            'scores.*.min'      => 'الدرجة لا يمكن أن تكون أقل من صفر.',
        ]);

        $manual = [];
        $auto   = 0;
        $total  = 0;

        foreach ($attempt->quiz->questions as $question) {
            $total += $question->points;

            if ($question->type === 'essay') {
                $given = (int) $request->input("scores.{$question->id}", 0);
                $manual[$question->id] = max(0, min($given, $question->points));
            } elseif (AnswerChecker::isCorrect($attempt->answers[$question->id] ?? null, $question)) {
                $auto += $question->points;
            }
        }

        $score      = $auto + array_sum($manual);
        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;

        $attempt->update([
            'manual_scores' => $manual,
            'score'         => $score,
            'passed'        => $percentage >= $attempt->quiz->pass_score,
            'needs_grading' => false,
            'graded_at'     => now(),
        ]);

        return back()->with('success', 'تم تصحيح الدرجة وحفظها.');
    }

    // --- Admin takes a quiz on behalf of a student ---
    public function take(Quiz $quiz)
    {
        $quiz->load(['questions' => fn ($q) => $q->orderBy('position')->orderBy('id')]);
        $students = Student::where('status', 'active')->get();
        return view('quizzes.take', compact('quiz', 'students'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'answers'    => 'required|array',
        ]);

        $quiz->load(['questions' => fn ($q) => $q->orderBy('position')->orderBy('id')]);
        $answers = $request->answers ?? [];

        [$score, $total, $needsGrading] = $this->gradeAnswers($quiz, $answers);

        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
        $passed     = !$needsGrading && $percentage >= $quiz->pass_score;

        $attempt = QuizAttempt::create([
            'quiz_id'       => $quiz->id,
            'student_id'    => $request->student_id,
            'answers'       => $answers,
            'score'         => $score,
            'total_points'  => $total,
            'passed'        => $passed,
            'needs_grading' => $needsGrading,
            'completed_at'  => now(),
        ]);

        return redirect()->route('quizzes.results', ['quiz' => $quiz->id, 'attempt' => $attempt->id]);
    }

    public function results(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::with(['student', 'quiz.questions' => fn ($q) => $q->orderBy('position')->orderBy('id')])
            ->findOrFail($request->attempt);
        abort_unless($attempt->quiz_id === $quiz->id, 404);

        return view('quizzes.results', compact('quiz', 'attempt'));
    }

    // ------------------------------------------------------------------
    // Shared helpers
    // ------------------------------------------------------------------

    /**
     * Grade an answers payload for any question type.
     * Returns [autoScore, totalPoints, needsGrading].
     */
    public static function gradeAnswers(Quiz $quiz, array &$answers): array
    {
        $score        = 0;
        $total        = 0;
        $needsGrading = false;

        foreach ($quiz->questions as $question) {
            $total += $question->points;
            $given  = $answers[$question->id] ?? null;

            if ($question->type === 'essay') {
                $needsGrading = true;
            } elseif (AnswerChecker::isCorrect($given, $question)) {
                $score += $question->points;
            }

            // Store a canonical form so review/leaderboard reads stay consistent.
            $answers[$question->id] = AnswerChecker::stored($given, $question);
        }

        return [$score, $total, $needsGrading];
    }

    private function validateQuizForm(Request $request): void
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'lecture_id'  => 'nullable|exists:lectures,id',
            'pass_score'  => 'required|integer|min:0|max:100',
            'status'      => 'required|in:published,draft',
            'questions'              => 'required|array|min:1',
            'questions.*.id'         => 'nullable|integer|exists:questions,id',
            'questions.*.type'       => 'required|in:' . implode(',', array_keys(Question::TYPES)),
            'questions.*.text'       => 'required|string',
            'questions.*.points'     => 'required|integer|min:1|max:100',
        ];

        foreach ((array) $request->input('questions', []) as $i => $q) {
            $type    = $q['type'] ?? 'mcq';
            $options = array_values(array_filter((array) ($q['options'] ?? []), fn ($o) => trim((string) $o) !== ''));

            switch ($type) {
                case 'mcq':
                    $maxIndex = max(0, count($options) - 1);
                    $rules["questions.$i.options"]    = 'required|array|min:2|max:8';
                    $rules["questions.$i.options.*"]  = 'required|string|max:500';
                    $rules["questions.$i.correct"]    = "required|integer|min:0|max:$maxIndex";
                    break;

                case 'true_false':
                    $rules["questions.$i.correct"] = 'required|in:true,false';
                    break;

                case 'fill_blank':
                    $rules["questions.$i.correct"]   = 'required|string|max:500';
                    $rules["questions.$i.accepted"]  = 'nullable|array';
                    $rules["questions.$i.accepted.*"] = 'nullable|string|max:500';
                    break;

                case 'matching':
                    $rules["questions.$i.pairs"]       = 'required|array|min:2';
                    $rules["questions.$i.pairs.*.left"]  = 'required|string|max:500';
                    $rules["questions.$i.pairs.*.right"] = 'required|string|max:500';
                    break;

                case 'ordering':
                    $rules["questions.$i.items"]   = 'required|array|min:2';
                    $rules["questions.$i.items.*"] = 'required|string|max:500';
                    break;

                // essay — no extra fields
            }
        }

        $request->validate($rules, [
            'questions.required'          => 'أضف سؤالاً واحداً على الأقل.',
            'questions.min'               => 'أضف سؤالاً واحداً على الأقل.',
            'questions.*.type.required'   => 'اختر نوع السؤال.',
            'questions.*.type.in'         => 'نوع السؤال غير صحيح.',
            'questions.*.text.required'   => 'نص السؤال مطلوب.',
            'questions.*.points.required' => 'نقاط السؤال مطلوبة.',
            'questions.*.points.min'      => 'النقاط يجب أن تكون 1 على الأقل.',
            'questions.*.options.min'     => 'أضف خيارين على الأقل للسؤال.',
            'questions.*.options.max'     => 'الحد الأقصى 8 خيارات.',
            'questions.*.options.*.required' => 'لا يمكن ترك خيار فارغ.',
            'questions.*.correct.required' => 'حدد الإجابة الصحيحة.',
            'questions.*.correct.in'      => 'الإجابة الصحيحة غير صحيحة.',
            'questions.*.pairs.required'  => 'أضف زوجين على الأقل للتوصيل.',
            'questions.*.pairs.*.left.required'  => 'نص العنصر الأيمن مطلوب.',
            'questions.*.pairs.*.right.required' => 'نص العنصر الأيسر مطلوب.',
            'questions.*.items.min'       => 'أضف عنصرين على الأقل للترتيب.',
        ]);
    }

    private function persistQuestion(Quiz $quiz, array $q, int $position, ?Question $existing = null): Question
    {
        $data = [
            'type'           => $q['type'],
            'question_text'  => $q['text'],
            'points'         => (int) $q['points'],
            'position'       => $position,
            'options'        => null,
            'data'           => null,
            'correct_answer' => null,
            'option_a'       => null,
            'option_b'       => null,
            'option_c'       => null,
            'option_d'       => null,
        ];

        switch ($q['type']) {
            case 'mcq':
                $options = array_values(array_filter(array_map(
                    fn ($o) => trim((string) $o),
                    (array) ($q['options'] ?? [])
                ), fn ($o) => $o !== ''));

                $data['options']  = $options;
                $data['correct_answer'] = chr(97 + (int) $q['correct']);

                // Keep legacy columns mirrored for the first 4 options so any
                // older code reading option_a..d stays consistent.
                foreach (range(0, 3) as $i) {
                    $data['option_' . chr(97 + $i)] = $options[$i] ?? null;
                }
                break;

            case 'true_false':
                $data['correct_answer'] = $q['correct'];
                break;

            case 'fill_blank':
                $data['correct_answer'] = trim($q['correct']);
                $accepted = array_values(array_unique(array_filter(array_map(
                    fn ($a) => trim((string) $a),
                    (array) ($q['accepted'] ?? [])
                ), fn ($a) => $a !== '')));

                if ($accepted) {
                    $data['data'] = ['accepted' => $accepted];
                }
                break;

            case 'matching':
                $data['data'] = [
                    'pairs' => array_values(array_map(fn ($p) => [
                        'left'  => trim((string) ($p['left'] ?? '')),
                        'right' => trim((string) ($p['right'] ?? '')),
                    ], (array) ($q['pairs'] ?? []))),
                ];
                break;

            case 'ordering':
                $data['options'] = array_values(array_filter(array_map(
                    fn ($i) => trim((string) $i),
                    (array) ($q['items'] ?? [])
                ), fn ($i) => $i !== ''));
                break;
        }

        if ($existing) {
            $existing->update($data);
            return $existing;
        }

        return $quiz->questions()->create($data);
    }

    private function syncQuestions(Request $request, Quiz $quiz): void
    {
        $keepIds = [];

        foreach (array_values($request->input('questions', [])) as $position => $q) {
            $existing = null;

            if (!empty($q['id'])) {
                $existing = $quiz->questions()->whereKey($q['id'])->first();
            }

            $question = $this->persistQuestion($quiz, $q, $position, $existing);
            $keepIds[] = $question->id;
        }

        // Remove questions that were deleted from the form. Historical
        // attempts keep their stored score; deleted questions simply stop
        // appearing in their review.
        $quiz->questions()->whereNotIn('id', $keepIds)->delete();
    }
}
