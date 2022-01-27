<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'otp',
        'otp_expire',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expire'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expire' => 'datetime',
    ];

   

    function generateCodeNumber() {
        $number = mt_rand(1,9999); // better than rand()
    
        // call the same function if the barcode exists already
        if ($this->CodeNumberExists($number)) {
            return generateCodeNumber();
        }
    
        // otherwise, it's valid and can be used
        return $number;
        }
    
        function CodeNumberExists($number) {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        return User::whereOtp($number)->exists();
        }
		
		 public function getJWTIdentifier () {
        return $this->getKey();
    }

    public function getJWTCustomClaims () {
        return [];
    }

    public function getAuthIdentifierName () {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getAuthIdentifier () {
        // TODO: Implement getAuthIdentifier() method.
    }

    public function getAuthPassword () {
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken () {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken ($value) {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName () {
        // TODO: Implement getRememberTokenName() method.
    }
}
