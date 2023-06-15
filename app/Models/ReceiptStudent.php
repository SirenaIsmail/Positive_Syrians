<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptStudent extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'user_id',
        'Debit',
        'description',
        'date',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function FundAccount()
    {
        return $this->hasOne(FundAccount::class, 'fund_account_id','id');
    }

    public function studentAccount()
    {
        return $this->hasOne(StudentAccount::class, 'student_account_id','id');
    }


}
