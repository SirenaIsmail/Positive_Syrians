<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attend extends Model
{
    use HasFactory;
    protected $fillable = [
        'history_id',
        'date_id',
        'classroom_id',
        'lesson_number',
        'state',
    ];

    public function histories()
    {
        return $this->belongsTo(History::class, 'history_id');
    }

    public function dates()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }

    public function classrooms()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}
