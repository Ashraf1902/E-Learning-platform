<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id', 'subscription_id', 'amount', 'method',
        'reference_number', 'phone_number', 'status', 'admin_note', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'fawry'         => 'فوري',
            'vodafone_cash' => 'فودافون كاش',
            default         => $this->method,
        };
    }
}