<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referance extends Model
{
    use HasFactory;
    protected $fillable = [
        'link',
        'lesson_number',
        'subject_trainer_id'
    ];

    public function trainerProfs()
    {
        return $this->belongsTo(TrainerProfile::class, 'trainer_id');
    }

    public function subjectTrainers()
    {
        return $this->belongsTo(SubjectTrainer::class, 'subject_trainer_id');
    }

    public function comments()
    {
        return $this->hasOne(Comment::class, 'comment_id','id');
    }
}
