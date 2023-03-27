<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'model',
        'file',
        'branch_id',
    ];

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
