<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'parent_phone', 'enrolled_at', 'status', 'level'];

    public function getLevelLabelAttribute(): string
    {
        return match($this->level) {
            'first'  => 'الصف الأول بكالوريا',
            'second' => 'الصف الثاني بكالوريا',
            'third'  => 'الصف الثالث الاعدادي',
            default  => 'غير محدد',
        };
    }

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'lecture_student')
                    ->withPivot('completed')
                    ->withTimestamps();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function accessCodeUsages()
    {
        return $this->hasMany(AccessCodeUsage::class);
    }

    /**
     * IDs of lectures this student unlocked through access codes.
     */
    public function unlockedLectureIds()
    {
        return AccessCodeLecture::whereIn(
            'access_code_id',
            $this->accessCodeUsages()->select('access_code_id')
        )->pluck('lecture_id');
    }

    public function hasUnlockedLectures(): bool
    {
        return $this->unlockedLectureIds()->isNotEmpty();
    }

    public function hasUnlockedLecture(Lecture $lecture): bool
    {
        return $this->unlockedLectureIds()->contains($lecture->id);
    }

    /**
     * True when the student can reach the subscription-gated portal area:
     * either an active subscription or at least one code-unlocked lecture.
     */
    public function hasPortalAccess(): bool
    {
        return $this->hasActiveSubscription() || $this->hasUnlockedLectures();
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>=', now()->toDateString())
            ->latest('ends_at')
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }
}