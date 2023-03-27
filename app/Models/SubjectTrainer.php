<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTrainer extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'trainer_id',
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');

    }

    public function trainerProfs()
    {
        return $this->belongsTo(TrainerProfile::class, 'trainer_id');

    }

    public function referaces()
    {
        return $this->hasMany(Referance::class, 'referance_id','id');
    }
}
