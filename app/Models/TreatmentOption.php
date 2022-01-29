<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentOption extends Model
{
    use HasFactory;
    public function treatment(){
        return $this->belongsTo(Treatment::class,'treatment');
    }
    public  function treatmentOptionPackage(){
        return $this->hasMany(TreatmentOptionPackage::class,'treatmentoption_id','id');
    }
}
