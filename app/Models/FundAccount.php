<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAccount extends Model
{
    use HasFactory;


    protected $fillable = [
       
        'receipt_id',
        'withdraw_id',
        'Debit',
        'Credit',
        'description',
        'date',
    ];

    public function receiptStudent()
    {
        return $this->belongsTo(ReceiptStudent::class, 'receipt_id');
    }

    
    public function withdraw()
    {
        return $this->belongsTo(Withdraw::class, 'withdraw_id');
    }
    
}
