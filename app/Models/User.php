<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable implements JWTSubject
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'roll_number',
        'first_name',
        'last_name',
        'birth_day',
        'branch_id',
        'phone_number',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function cards(){
        return $this->hasOne(Card::class,'card_id' , 'id');
    }

    public function trainerProfs(){
        return $this->hasOne(TrainerProfile::class,'trainer_profile_id' , 'id');
    }

    public function comment()
    {
        return $this->hasOne(Comment::class, 'comment_id','id');
    }


    public function StudentAccount()
    {
        return $this->hasMany(StudentAccount::class, 'student_account_id','id');
    }


    public function ReceiptStudent()
    {
        return $this->hasMany(ReceiptStudent::class, 'receipt_student_id','id');
    }

    public function ProcessingFee()
    {
        return $this->hasMany(ProcessingFee::class, 'processing_fee_id','id');
    }

    public function withdraw()
    {
        return $this->hasMany(Withdraw::class, 'withdraw_id','id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
