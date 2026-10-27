<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCodeLecture extends Model
{
    protected $fillable = ['access_code_id', 'lecture_id'];

    public function code()
    {
        return $this->belongsTo(AccessCode::class, 'access_code_id');
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
