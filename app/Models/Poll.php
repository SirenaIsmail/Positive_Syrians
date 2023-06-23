<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'poll_date',
        'phone_numb',
        'first',
        'branch_id',

    ];

    public function subjects(){
        return $this->belongsTo(Subject::class,'subject_id' , 'id');

    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
