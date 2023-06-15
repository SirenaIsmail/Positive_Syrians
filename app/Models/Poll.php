<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name_ar',
        'full_name_en',
        'mother_name',
        'address',
        'poll_date',
        'phone_numb',
        'whatsapp_numb',
        'first_subj',
        'secound_subj',
        'third_subj',
        'first_time',
        'secound_time',
        'third_time',
        'notice',
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
