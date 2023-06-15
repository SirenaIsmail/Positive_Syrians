<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessingFee extends Model
{
    use HasFactory;


    protected $fillable = [
       
        'user_id',
        'amount',
        'description',
        'date',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function Studentaccount()
    {
        return $this->hasOne(StudentAccount::class, 'student_account_id','id');
    }
    

}
