<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'flag',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courses(){
        return $this->hasMany(Course::class,'course_id' , 'id');
    }

    public function subjectTrainer(){
        return $this->hasMany(SubjectTrainer::class,'subject_trainer_id' , 'id');
    }

    public function referances()
    {
        return $this->hasMany(Referance::class, 'referance_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_id');
    }

    public function trainerRatings()
    {
        return $this->hasMany(TrainerRating::class, 'rating_id','id');
    }
}
