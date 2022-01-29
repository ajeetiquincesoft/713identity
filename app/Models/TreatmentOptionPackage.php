<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentOptionPackage extends Model
{
    use HasFactory;
    public function treatment(){
        return $this->belongsTo(Treatment::class,'treatment_id');
    }
    public  function treatmentOption(){
        return $this->belongsTo(TreatmentOption::class,'treatmentoption_id');
    }
}
