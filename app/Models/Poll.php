<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'first_subj',
        'secound_subj',
        'third_subj',
        'first_time',
        'secound_time',
        'third_time',
        'poll_date',
    ];

    public function subjects(){
        return $this->belongsTo(Subject::class,'subject_id' , 'id');
    }
}
