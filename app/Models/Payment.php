<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'subscribe_id',
        'ammount',
        'subammount',
        'date',
    ];

    public function proceedes()
    {
        return $this->hasMany(Proceed::class, 'proceed_id','id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function subscribes()
    {
        return $this->belongsTo(Subscribe::class, 'subscribe_id');
    }
  
    public function studentAccount()
    {
        return $this->hasOne(Proceed::class, 'stusent_account_id','id');
    }

   




}
