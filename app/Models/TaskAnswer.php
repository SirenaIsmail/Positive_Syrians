<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'answer',
        'flag',
        'student_id',
    ];

    public function tasks()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function studentProfiles()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }
}
