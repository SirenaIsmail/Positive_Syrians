<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'barcode',
        'branch_id',
    ];



    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'history_id','id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function studentProfs(){
        return $this->hasOne(StudentProfile::class,'student_id' , 'id');
    }

    public function subscribes(){
        return $this->hasMany(Subscribe::class,'subscribe_id' , 'id');
    }
}
