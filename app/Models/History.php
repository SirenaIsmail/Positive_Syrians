<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_id'
    ];

    public function cards()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function attends()
    {
        return $this->hasMany(Attend::class, 'attend_id','id');
    }
}
