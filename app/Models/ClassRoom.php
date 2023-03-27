<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'size',
    ];

    public function attends(){
        return $this->hasOne(Attend::class,'attend_id' , 'id');
    }
}
