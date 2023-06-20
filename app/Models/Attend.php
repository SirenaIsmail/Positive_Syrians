<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attend extends Model
{
    use HasFactory;
    protected $fillable = [
        'card_id',
        'course_id',
        'date',
        'state',
    ];

    public function cards()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


}
