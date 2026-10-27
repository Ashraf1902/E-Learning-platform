<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'lecture_id', 'pass_score', 'status'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('position')->orderBy('id');
    }

    public function hasEssayQuestions(): bool
    {
        return $this->questions()->where('type', 'essay')->exists();
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
