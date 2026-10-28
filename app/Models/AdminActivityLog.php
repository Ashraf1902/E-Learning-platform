<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'path',
        'route',
        'status_code',
        'ip',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function recordFromRequest(Request $request, ?int $statusCode = null): self
    {
        return self::create([
            'user_id'     => $request->user()?->id,
            'method'      => $request->method(),
            'path'        => Str::limit('/' . ltrim($request->path(), '/'), 250, ''),
            'route'       => $request->route()?->getName(),
            'status_code' => $statusCode,
            'ip'          => $request->ip(),
            'user_agent'  => Str::limit((string) $request->userAgent(), 2000, ''),
        ]);
    }

    public function actionLabel(): string
    {
        return trim($this->method . ' ' . ($this->route ?: $this->path));
    }
}
