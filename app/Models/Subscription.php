<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['student_id', 'subscription_plan_id', 'status', 'starts_at', 'ends_at'];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isCurrentlyActive(): bool
    {
        return $this->status === 'active'
            && $this->ends_at
            && $this->ends_at->isFuture();
    }
}