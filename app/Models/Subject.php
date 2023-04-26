<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'content',
        'price',
        'houers',
        'number_of_lessons',
    ];

    public function polls(){
        return $this->hasMany(Poll::class,'poll_id');
    }

    public function subjectTrainer(){
        return $this->hasMany(SubjectTrainer::class,'subject_trainer_id' , 'id');
    }

    public function subscribe(){
        return $this->hasMany(Subscribe::class,'subscribe_id' , 'id');
    }

    public function course(){
        return $this->hasMany(Course::class,'course_id' , 'id');
    }
}
