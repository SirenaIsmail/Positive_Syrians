<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'subject_id',
        'trainer_id',
        'min_students',
        'max_students',
       // 'approved',
        'start',
        'end',
    ];

    public function histories()
    {
        return $this->hasMany(History::class, 'history_id','id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_id','id');
    }

    public function questionBanks()
    {
        return $this->hasMany(QuestionBank::class, 'Question_bank_id','id');
    }

    public function attends()
    {
        return $this->hasMany(Attend::class, 'attend_id','id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function subjects(){
        return $this->belongsTo(Subject::class,'subject_id' );
    }

    public function trainerProfs()
    {
        return $this->belongsTo(TrainerProfile::class, 'trainer_id');
    }
}
