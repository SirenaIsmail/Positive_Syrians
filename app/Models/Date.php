<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    protected $fillable = [
        'day',
        'mounth',
        'year',
    ];

    public function topCourses()
    {
        return $this->hasMany(TopCourse::class, 'top_id','id');
    }

    public function proceedes()
    {
        return $this->hasMany(Proceed::class, 'proceed_id','id');
    }

    public function trainerRatings()
    {
        return $this->hasMany(TrainerRating::class, 'rating_id','id');
    }
}
