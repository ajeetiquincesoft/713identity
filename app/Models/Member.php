<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Contracts\Auth\Authenticatable;



class Member extends Model implements JWTSubject, Authenticatable
{
	protected $table = 'users';
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
