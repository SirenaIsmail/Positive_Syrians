<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'card_id',
        'branch_id',
        'state',
        'date_id',
    ];


    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_id','id');
    }

    public function topCourses()
    {
        return $this->hasMany(TopCourse::class, 'top_id','id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function cards()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function dates()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }
}
