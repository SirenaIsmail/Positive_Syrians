<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_id',
        'subscribe_id',
        'trainer_id',
        'rating',
        'note',
    ];

    public function dates()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }

    public function subscribes()
    {
        return $this->belongsTo(Subscribe::class, 'subscribe_id');
    }

    public function trainerProfs()
    {
        return $this->belongsTo(TrainerProfile::class, 'trainer_id');
    }
}
