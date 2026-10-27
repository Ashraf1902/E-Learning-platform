<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'video_url', 'video_provider', 'video_id',
        'category', 'status', 'duration_minutes', 'user_id', 'level',
        'show_leaderboard', 'leaderboard_limit'
    ];

    protected $casts = [
        'show_leaderboard' => 'boolean',
        'leaderboard_limit' => 'integer',
    ];

    public function getLevelLabelAttribute(): string
    {
        return match($this->level) {
            'first'  => 'الصف الأول بكالوريا',
            'second' => 'الصف الثاني بكالوريا',
            'third'  => 'الصف الثالث الاعدادي',
            default  => 'جميع المستويات',
        };
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'lecture_student')
                    ->withPivot('completed')
                    ->withTimestamps();
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function materials()
    {
        return $this->hasMany(LectureMaterial::class);
    }

    // ── Video helpers ──

    /**
     * Extract provider + video ID from a pasted URL.
     * Supports YouTube, Vimeo, and direct/self-hosted video files.
     * For YouTube/Vimeo we store only the ID — never exposing the raw URL
     * to the frontend. Direct file links are stored as-is (behind a redirect).
     */
    public static function parseVideoUrl(?string $url): array
    {
        if (!$url) return ['provider' => 'youtube', 'id' => null];

        $url = trim($url);
        if (!preg_match('~^https?://~i', $url)) $url = 'https://' . $url;

        // Direct video file (mp4/webm/ogg/m4v/mov) — self-hosted or CDN.
        $path = (string) parse_url($url, PHP_URL_PATH);
        if (preg_match('~\.(mp4|webm|ogg|m4v|mov)$~i', $path)) {
            return ['provider' => 'url', 'id' => $url];
        }

        // YouTube: youtu.be/ID, youtube.com/watch?v=ID (any query order),
        // /embed/ID, /shorts/ID, /live/ID, /v/ID
        if (preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:embed/|shorts/|live/|v/))([a-zA-Z0-9_-]{6,})~i', $url, $m)) {
            return ['provider' => 'youtube', 'id' => $m[1]];
        }
        if (preg_match('~youtube(?:-nocookie)?\.com/watch~i', $url)) {
            $query = (string) parse_url($url, PHP_URL_QUERY);
            parse_str($query, $params);
            if (!empty($params['v']) && preg_match('/^[a-zA-Z0-9_-]{6,}$/', $params['v'])) {
                return ['provider' => 'youtube', 'id' => $params['v']];
            }
        }

        // Vimeo: vimeo.com/ID, player.vimeo.com/video/ID, channels/.../ID
        if (preg_match('~vimeo\.com/(?:video/|channels/[^/]+/)?(\d+)~i', $url, $m)) {
            return ['provider' => 'vimeo', 'id' => $m[1]];
        }

        return ['provider' => 'youtube', 'id' => null];
    }

    public function hasVideo(): bool
    {
        return !empty($this->video_id);
    }

    public function isSelfHosted(): bool
    {
        return in_array($this->video_provider, ['upload', 'url'], true);
    }
}