<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    protected $fillable = [
        'code', 'type', 'description', 'max_uses',
        'expires_at', 'is_active', 'user_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
        'max_uses'   => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class, 'access_code_lectures')
            ->withTimestamps();
    }

    public function usages()
    {
        return $this->hasMany(AccessCodeUsage::class);
    }

    public function usedCount(): int
    {
        return $this->usages()->count();
    }

    public function usesRemaining(): int
    {
        return max(0, $this->max_uses - $this->usedCount());
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Why this code cannot be redeemed right now, or null when it is usable.
     */
    public function unusableReason(): ?string
    {
        if (!$this->is_active) {
            return 'هذا الكود غير مفعّل.';
        }

        if ($this->isExpired()) {
            return 'انتهت صلاحية هذا الكود.';
        }

        if ($this->usesRemaining() <= 0) {
            return 'وصل هذا الكود لأقصى عدد من الطلاب.';
        }

        return null;
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(substr(str_replace(['0', 'O', '1', 'I', 'L'], '', bin2hex(random_bytes(8))), 0, 8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Record that a student used this code and, for lecture codes,
     * attach the unlocked lectures to their portal.
     * Shared by admin grants and student redemptions.
     */
    public function grantTo(Student $student): void
    {
        AccessCodeUsage::firstOrCreate([
            'access_code_id' => $this->id,
            'student_id'     => $student->id,
        ]);

        if ($this->type === 'lecture') {
            $lectureIds = $this->lectures()->pluck('lectures.id');

            if ($lectureIds->isNotEmpty()) {
                $student->lectures()->syncWithoutDetaching($lectureIds);
            }
        }
    }
}
