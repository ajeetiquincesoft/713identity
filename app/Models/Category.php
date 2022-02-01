<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [
       
    ];

    public  function treatment(){
        return $this->hasMany(Treatment::class,'category_id','id')->where('status',1);
    }
}
