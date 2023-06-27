<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
    
    use HasFactory;
 

    protected $table = 'branches';
    protected $fillable = [
        'No',
        'name',
    ];

    public function classrooms(){
        return $this->hasOne(ClassRoom::class,'classroom_id' , 'id');
    }

    public function cards(){
        return $this->hasMany(Card::class,'card_id' , 'id');
    }

    public function courses(){
        return $this->hasMany(Course::class,'course_id' , 'id');
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'subscribe_id','id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_id','id');
    }

    public function questionBanks()
    {
        return $this->hasMany(QuestionBank::class, 'question_bank_id','id');
    }

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

    public function polls(){
        return $this->hasMany(Poll::class,'poll_id' , 'id');
    }
 
    public function FundAccount()
    {
        return $this->hasOne(FundAccount::class, 'fund_account_id','id');
    }



}
