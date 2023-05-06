<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'trainer_id',
        'course_id',
        'lesson_number',
        'options',
        'answer',
    ];

    protected $casts = ['options' => 'array'];




    public function taskAnswer()
    {
        return $this->hasMany(TaskAnswer::class, 'task_answer_id','id');
    }

    public function trainerProfs()
    {
        return $this->belongsTo(TrainerProfile::class, 'trainer_id');
    }

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
