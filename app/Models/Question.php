<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public const TYPES = [
        'mcq'         => 'اختيار من متعدد',
        'true_false'  => 'صح / خطأ',
        'fill_blank'  => 'أكمل الفراغ',
        'essay'       => 'سؤال مقالي',
        'matching'    => 'توصيل',
        'ordering'    => 'ترتيب',
    ];

    public const OPTION_LABELS = ['أ', 'ب', 'ج', 'د', 'هـ', 'و', 'ز', 'ح'];

    protected $fillable = [
        'quiz_id', 'type', 'question_text',
        'option_a', 'option_b', 'option_c', 'option_d',
        'options', 'data', 'correct_answer', 'points', 'position',
    ];

    protected $casts = [
        'options'  => 'array',
        'data'     => 'array',
        'position' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * The effective choices for choice-based questions.
     * MCQ falls back to the legacy option_a..d columns, so old
     * production questions keep rendering without any data migration.
     */
    public function getOptionListAttribute(): ?array
    {
        if ($this->type === 'mcq') {
            if (!empty($this->options) && is_array($this->options)) {
                return array_values($this->options);
            }

            return array_values(array_filter([
                $this->option_a, $this->option_b, $this->option_c, $this->option_d,
            ], fn ($o) => $o !== null && $o !== ''));
        }

        if ($this->type === 'true_false') {
            return ['true' => 'صح', 'false' => 'خطأ'];
        }

        return null;
    }

    public function isAutoGraded(): bool
    {
        return $this->type !== 'essay';
    }

    public function pairs(): array
    {
        return $this->data['pairs'] ?? [];
    }

    public function items(): array
    {
        return array_values($this->options ?? []);
    }

    public function acceptedAnswers(): array
    {
        $answers = array_merge(
            array_values(array_filter([$this->correct_answer])),
            $this->data['accepted'] ?? []
        );

        return array_values(array_unique($answers));
    }

    /** Human-readable label of the correct answer (for reviews/results). */
    public function correctLabel(): string
    {
        return match ($this->type) {
            'mcq' => $this->optionForLetter($this->correct_answer)
                ?? (string) $this->correct_answer,
            'true_false' => $this->correct_answer === 'true' ? 'صح' : 'خطأ',
            'fill_blank' => $this->acceptedAnswers() ? implode(' أو ', $this->acceptedAnswers()) : '—',
            'matching' => collect($this->pairs())
                ->map(fn ($p) => "{$p['left']} ↔ {$p['right']}")
                ->implode('  |  '),
            'ordering' => implode(' ← ', $this->items()),
            'essay' => 'تصحيح يدوي',
            default => (string) $this->correct_answer,
        };
    }

    /** Human-readable label of the student's given answer. */
    public function givenLabel(mixed $given): string
    {
        if ($given === null || $given === '' || $given === []) {
            return '—';
        }

        return match ($this->type) {
            'mcq' => $this->optionForLetter($given) ?? (string) $given,
            'true_false' => in_array($given, ['true', '1', true], true) ? 'صح' : 'خطأ',
            'matching' => collect((array) $given)
                ->map(fn ($right, $leftIndex) => ($this->pairs()[$leftIndex]['left'] ?? "?") . ' ↔ ' . $right)
                ->implode('  |  '),
            'ordering' => implode(' ← ', array_values((array) $given)),
            default => is_array($given) ? implode(', ', $given) : (string) $given,
        };
    }

    private function optionForLetter(mixed $letter): ?string
    {
        $list = $this->option_list;
        if (!is_string($letter) || $list === null) {
            return null;
        }

        $index = strpos('abcdefgh', mb_strtolower($letter));
        if ($index === false || !isset($list[$index])) {
            return null;
        }

        return $list[$index];
    }
}
