<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceed extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_id',
        'branch_id',
        'payment_id',
    ];

    public function dates()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function payments()
    {
        return $this->belongsTo(Payment::class, 'paymet_id');
    }
}
