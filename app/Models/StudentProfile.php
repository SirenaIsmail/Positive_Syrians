<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'card_id'
    ];

    public function histories()
    {
        return $this->hasMany(History::class, 'history_id','id');
    }

    public function taskAnswer()
    {
        return $this->hasMany(TaskAnswer::class, 'tsk_answer_id','id');
    }

    public function cards()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }
}
