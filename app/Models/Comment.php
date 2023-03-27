<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'user_id',
        'referance_id',
        'comment_id'
    ];

    public function referances()
    {
        return $this->belongsTo(Referance::class, 'referance_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment()
    {
        return $this->hasOne(Comment::class, 'comment_id','id');
    }

    public function comments()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

}
