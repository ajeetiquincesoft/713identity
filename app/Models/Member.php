<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Contracts\Auth\Authenticatable;



class Member extends Model implements JWTSubject, Authenticatable
{
    protected $table = 'users';
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expire'
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
    }

    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id', 'id');
    }
    public  function treatment()
    {
        return $this->hasMany(Treatment::class, 'treatment_id', 'id');
    }
    public  function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id', 'id');
    }

    public  function payments()
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }
}
