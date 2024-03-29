<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'Number',
        'className',
        'size',
        'branch_id'
    ];

    public function branches(){
        return $this->belongsTo(Branch::class,'branch_id' );
    }
}
