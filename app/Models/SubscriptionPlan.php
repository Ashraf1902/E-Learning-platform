<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'description', 'price', 'duration', 'duration_days', 'is_active'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getDurationLabelAttribute(): string
    {
        return match($this->duration) {
            'month' => 'شهري',
            'term'  => 'فصل دراسي',
            'year'  => 'سنوي',
            default => $this->duration,
        };
    }
}