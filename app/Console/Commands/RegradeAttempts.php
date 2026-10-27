<?php

namespace App\Console\Commands;

use App\Models\QuizAttempt;
use App\Support\AnswerChecker;
use Illuminate\Console\Command;

class RegradeAttempts extends Command
{
    protected $signature = 'quizzes:regrade';

    protected $description = 'Recompute all quiz attempt scores with AnswerChecker and canonicalize stored answers';

    public function handle(): int
    {
        $attempts = QuizAttempt::with('quiz.questions')->get();
        $regraded = 0;

        foreach ($attempts as $attempt) {
            if (!$attempt->quiz || $attempt->quiz->questions->isEmpty()) {
                continue;
            }

            $score   = 0;
            $total   = 0;
            $answers = $attempt->answers ?? [];

            foreach ($attempt->quiz->questions as $question) {
                $total += $question->points;
                $given  = $answers[$question->id] ?? null;

                if (AnswerChecker::isCorrect($given, $question)) {
                    $score += $question->points;
                }

                $answers[$question->id] = AnswerChecker::canonical($given, $question) ?? $given;
            }

            $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
            $passed     = $percentage >= $attempt->quiz->pass_score;

            if ($attempt->score !== $score
                || $attempt->total_points !== $total
                || $attempt->passed !== $passed
                || $answers !== $attempt->answers) {
                $attempt->update([
                    'answers'      => $answers,
                    'score'        => $score,
                    'total_points' => $total,
                    'passed'       => $passed,
                ]);
                $regraded++;
            }
        }

        $this->info("Regraded {$regraded} of {$attempts->count()} attempts.");

        return self::SUCCESS;
    }
}
