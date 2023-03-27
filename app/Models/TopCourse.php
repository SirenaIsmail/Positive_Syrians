<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscribe_id',
        'date_id',
        'branch_id'
    ];

    public function subscribes()
    {
        return $this->belongsTo(Subscribe::class, 'subscribe_id');
    }

    public function dates()
    {
        return $this->belongsTo(Date::class, 'date_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
