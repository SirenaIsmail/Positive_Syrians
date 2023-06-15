<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAccount extends Model
{
    use HasFactory;
   
    protected $fillable = [
       
        'user_id',
        'payment_id',
        'receipt_id',
        'processing_id',
        'withdraw_id',
        'type',
        'Debit',
        'Credit',
        'date',
    ];

    
    public function payments()
    {
        return $this->belongsTo(Payment::class, 'paymet_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiptStudent()
    {
        return $this->belongsTo(ReceiptStudent::class, 'receipt_id');
    }

    public function ProcessingFee()
    {
        return $this->belongsTo(ProcessingFee::class, 'processing_id');
    }

    public function withdraw()
    {
        return $this->belongsTo(Withdraw::class, 'withdraw_id');
    }

}
