<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_id',
        'branch_id',
        'trainer_id'
    ];

    public function dates()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function trainerProfs()
    {
        return $this->belongsTo(TrainerProfile::class, 'trainer_id');
    }
}
