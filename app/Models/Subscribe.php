<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'card_id',
        'branch_id',
        'date',
        'state',

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

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }



    protected static function boot()
    {
        parent::boot();

        static::updated(function ($subscription) {
            Payment::where('subscribe_id', $subscription->id)->update(['branch_id' => $subscription->branch_id]);
        });
    }






}
