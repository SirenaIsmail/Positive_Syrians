<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'No',
        'size',
        'branch_id'
    ];

    public function attends(){
        return $this->hasOne(Attend::class,'attend_id' , 'id');
    }

    public function branches(){
        return $this->belongsTo(Branch::class,'branch_id' );
    }
}
