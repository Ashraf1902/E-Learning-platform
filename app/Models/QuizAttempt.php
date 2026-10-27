<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'answers',
        'score', 'total_points', 'passed',
        'manual_scores', 'needs_grading', 'graded_at', 'completed_at',
    ];

    protected $casts = [
        'answers'       => 'array',
        'manual_scores' => 'array',
        'passed'        => 'boolean',
        'needs_grading' => 'boolean',
        'graded_at'     => 'datetime',
        'completed_at'  => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getPercentageAttribute(): int
    {
        if ($this->total_points === 0) return 0;
        return (int) round(($this->score / $this->total_points) * 100);
    }

    public function isPendingGrading(): bool
    {
        return $this->needs_grading && !$this->graded_at;
    }
}
