<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTime extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'time_id',
    ];

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function times()
    {
        return $this->belongsTo(Time::class, 'time_id');
    }
}
