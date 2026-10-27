<?php

namespace App\Support;

use App\Models\AccessCodeUsage;
use App\Models\Lecture;
use App\Models\LectureMaterial;
use App\Models\Payment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Builds the "what's new" feed for the signed-in user from real activity.
 * There is no notifications content table: every item is derived from an
 * existing record, so nothing can go stale or be fabricated. Per-item read
 * state lives in notification_reads; the legacy seen marker still applies
 * to older items.
 */
class NotificationFeed
{
    private const WINDOW_DAYS = 30;
    private const LIMIT = 12;

    public function for(User $user, int $limit = self::LIMIT, string $filter = 'all'): array
    {
        Carbon::setLocale(app()->getLocale());

        $items = $user->isAdmin()
            ? $this->forAdmin()
            : $this->forStudent($user);

        $items = $items
            ->sortByDesc(fn (array $item) => $item['time']->getTimestamp())
            ->values();

        // Older read markers predate per-item state. Treat anything at or
        // before that marker as read, then layer explicit reads on top.
        $seenAt = $user->notifications_seen_at;
        $keys = $items->pluck('key')->all();
        $readKeys = $keys === []
            ? []
            : $user->notificationReads()
                ->whereIn('notification_key', $keys)
                ->pluck('notification_key')
                ->all();
        $readKeys = array_flip($readKeys);

        $items = $items->map(function (array $item) use ($seenAt, $readKeys) {
            $legacyRead = $seenAt !== null && $item['time']->lessThanOrEqualTo($seenAt);
            $item['unread'] = !$legacyRead && !isset($readKeys[$item['key']]);
            $item['time_label'] = $item['time']->diffForHumans();

            return $item;
        });

        $unread = $items->where('unread', true)->count();

        if ($filter === 'unread') {
            $items = $items->where('unread', true)->values();
        }

        return [
            'items'  => $items->take($limit)->values(),
            'unread' => $unread,
        ];
    }

    private function forAdmin(): Collection
    {
        $since = now()->subDays(self::WINDOW_DAYS);
        $items = collect();

        // New student registrations.
        Student::query()
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->take(8)
            ->get()
            ->each(function (Student $student) use ($items) {
                $items->push($this->item(
                    'student-'.$student->id,
                    'users',
                    'gold',
                    __('notifications.admin.new_student'),
                    __('notifications.admin.new_student_body', [
                        'name'  => $student->name,
                        'level' => $this->levelLabel($student->level),
                    ]),
                    $student->created_at,
                    route('students.show', $student)
                ));
            });

        // Payments waiting for a decision.
        Payment::with('student')
            ->where('status', 'pending')
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->take(8)
            ->get()
            ->each(function (Payment $payment) use ($items) {
                $items->push($this->item(
                    'payment-'.$payment->id,
                    'card',
                    'amber',
                    __('notifications.admin.payment_pending'),
                    __('notifications.admin.payment_body', [
                        'amount'  => $this->money($payment->amount),
                        'student' => $payment->student?->name ?? '—',
                    ]),
                    $payment->created_at,
                    route('admin.payments.index')
                ));
            });

        // Lecture codes redeemed by students (registration codes are already
        // represented by the new-student item above).
        AccessCodeUsage::with(['code', 'student'])
            ->where('created_at', '>=', $since)
            ->whereHas('code', fn ($query) => $query->where('type', 'lecture'))
            ->latest('created_at')
            ->take(8)
            ->get()
            ->each(function (AccessCodeUsage $usage) use ($items) {
                if (!$usage->code) {
                    return;
                }

                $items->push($this->item(
                    'usage-'.$usage->id,
                    'key',
                    'blue',
                    __('notifications.admin.code_used'),
                    __('notifications.admin.code_body', [
                        'student' => $usage->student?->name ?? '—',
                        'code'    => $usage->code->code,
                    ]),
                    $usage->created_at,
                    route('access-codes.show', $usage->code)
                ));
            });

        // Quiz submissions, including essay answers that still need a human.
        QuizAttempt::with(['quiz', 'student'])
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $since)
            ->latest('completed_at')
            ->take(8)
            ->get()
            ->each(function (QuizAttempt $attempt) use ($items) {
                if (!$attempt->quiz) {
                    return;
                }

                $pending = $attempt->isPendingGrading();

                $items->push($this->item(
                    'attempt-'.$attempt->id,
                    'clipboard',
                    $pending ? 'amber' : 'blue',
                    __($pending ? 'notifications.admin.attempt_pending' : 'notifications.admin.attempt_submitted'),
                    __('notifications.admin.attempt_body', [
                        'student' => $attempt->student?->name ?? '—',
                        'quiz'    => $attempt->quiz->title,
                        'score'   => $attempt->percentage,
                    ]),
                    $attempt->completed_at,
                    route('quizzes.results', ['quiz' => $attempt->quiz_id, 'attempt' => $attempt->id])
                ));
            });

        // Lectures that went live.
        Lecture::query()
            ->where('status', 'published')
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->take(6)
            ->get()
            ->each(function (Lecture $lecture) use ($items) {
                $items->push($this->item(
                    'lecture-'.$lecture->id,
                    'video',
                    'green',
                    __('notifications.admin.lecture_published'),
                    __('notifications.admin.lecture_body', [
                        'title' => $lecture->title,
                        'level' => $this->levelLabel($lecture->level),
                    ]),
                    $lecture->created_at,
                    route('lectures.show', $lecture)
                ));
            });

        return $items;
    }

    private function forStudent(User $user): Collection
    {
        $student = $user->student;

        if (!$student) {
            return collect();
        }

        $since = now()->subDays(self::WINDOW_DAYS);
        $items = collect();

        $lectureIds  = $student->lectures()->pluck('lectures.id');
        $unlockedIds = $student->unlockedLectureIds();
        $subscribed  = $student->hasActiveSubscription();
        $accessible  = $subscribed ? $lectureIds : $unlockedIds;

        // Lectures assigned (subscribers) or unlocked with a code (everyone else).
        if ($subscribed) {
            $student->lectures()
                ->wherePivot('created_at', '>=', $since)
                ->orderByDesc('lecture_student.created_at')
                ->take(8)
                ->get()
                ->each(function (Lecture $lecture) use ($items) {
                    $items->push($this->item(
                        'lecture-'.$lecture->id,
                        'video',
                        'gold',
                        __('notifications.student.new_lecture'),
                        __('notifications.student.new_lecture_body', ['title' => $lecture->title]),
                        $lecture->pivot->created_at,
                        route('portal.lectures.show', $lecture)
                    ));
                });
        } else {
            $added = [];

            $student->accessCodeUsages()
                ->with(['code.lectures'])
                ->where('created_at', '>=', $since)
                ->latest('created_at')
                ->get()
                ->each(function (AccessCodeUsage $usage) use ($items, &$added) {
                    if (!$usage->code) {
                        return;
                    }

                    foreach ($usage->code->lectures as $lecture) {
                        if (isset($added[$lecture->id])) {
                            continue;
                        }

                        $added[$lecture->id] = true;

                        $items->push($this->item(
                            'lecture-'.$lecture->id,
                            'video',
                            'gold',
                            __('notifications.student.new_lecture'),
                            __('notifications.student.new_lecture_body', ['title' => $lecture->title]),
                            $usage->created_at,
                            route('portal.lectures.show', $lecture)
                        ));
                    }
                });
        }

        // Quizzes that became available.
        $quizQuery = Quiz::with('lecture')
            ->where('status', 'published')
            ->where('created_at', '>=', $since);

        if ($subscribed) {
            $quizQuery->where(function ($query) use ($lectureIds) {
                $query->whereIn('lecture_id', $lectureIds)->orWhereNull('lecture_id');
            });
        } else {
            $quizQuery->whereIn('lecture_id', $unlockedIds);
        }

        $quizQuery->latest('created_at')->take(8)->get()->each(function (Quiz $quiz) use ($items) {
            $body = $quiz->lecture
                ? __('notifications.student.new_quiz_body', [
                    'title'   => $quiz->title,
                    'lecture' => $quiz->lecture->title,
                ])
                : $quiz->title;

            $items->push($this->item(
                'quiz-'.$quiz->id,
                'clipboard',
                'blue',
                __('notifications.student.new_quiz'),
                $body,
                $quiz->created_at,
                route('portal.quizzes')
            ));
        });

        // Essay answers that were just graded.
        $student->quizAttempts()
            ->with('quiz')
            ->whereNotNull('graded_at')
            ->where('graded_at', '>=', $since)
            ->latest('graded_at')
            ->take(8)
            ->get()
            ->each(function (QuizAttempt $attempt) use ($items) {
                if (!$attempt->quiz) {
                    return;
                }

                $items->push($this->item(
                    'graded-'.$attempt->id,
                    'check-circle',
                    $attempt->passed ? 'green' : 'amber',
                    __('notifications.student.graded'),
                    __('notifications.student.graded_body', [
                        'quiz'  => $attempt->quiz->title,
                        'score' => $attempt->percentage,
                    ]),
                    $attempt->graded_at,
                    route('portal.results.review', $attempt->id)
                ));
            });

        // Subscription payment decisions.
        $student->payments()
            ->whereIn('status', ['paid', 'rejected'])
            ->where('updated_at', '>=', $since)
            ->latest('updated_at')
            ->take(6)
            ->get()
            ->each(function (Payment $payment) use ($items) {
                $paid = $payment->status === 'paid';

                $items->push($this->item(
                    'payment-'.$payment->id,
                    'card',
                    $paid ? 'green' : 'red',
                    __($paid ? 'notifications.student.payment_paid' : 'notifications.student.payment_rejected'),
                    __('notifications.student.payment_body', ['amount' => $this->money($payment->amount)]),
                    $payment->updated_at,
                    route('portal.subscription')
                ));
            });

        // New material uploaded to one of their lectures.
        LectureMaterial::with('lecture')
            ->whereIn('lecture_id', $accessible)
            ->where('created_at', '>=', $since)
            ->latest('created_at')
            ->take(6)
            ->get()
            ->each(function (LectureMaterial $material) use ($items) {
                if (!$material->lecture) {
                    return;
                }

                $items->push($this->item(
                    'material-'.$material->id,
                    'file',
                    'blue',
                    __('notifications.student.new_material'),
                    __('notifications.student.new_material_body', [
                        'title'   => $material->title,
                        'lecture' => $material->lecture->title,
                    ]),
                    $material->created_at,
                    route('portal.lectures.show', $material->lecture_id)
                ));
            });

        return $items;
    }

    private function item(
        string $key,
        string $icon,
        string $tone,
        string $title,
        string $body,
        mixed $time,
        string $url
    ): array {
        return [
            'key'   => $key,
            'icon'  => $icon,
            'tone'  => $tone,
            'title' => $title,
            'body'  => $body,
            'time'  => $time instanceof Carbon ? $time : Carbon::parse($time),
            'url'   => $url,
        ];
    }

    private function levelLabel(?string $level): string
    {
        $key = in_array($level, ['first', 'second', 'third', 'all'], true) ? $level : 'unknown';

        return __('notifications.levels.'.$key);
    }

    private function money(mixed $amount): string
    {
        $value = (float) $amount;
        $formatted = fmod($value, 1.0) === 0.0
            ? number_format($value, 0)
            : number_format($value, 2);

        return $formatted.' '.__('notifications.currency');
    }
}
