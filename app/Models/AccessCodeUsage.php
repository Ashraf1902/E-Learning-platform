<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCodeUsage extends Model
{
    protected $fillable = ['access_code_id', 'student_id'];

    public function code()
    {
        return $this->belongsTo(AccessCode::class, 'access_code_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
