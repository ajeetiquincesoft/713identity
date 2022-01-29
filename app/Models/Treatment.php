<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;
    protected $guarded = [
       
    ];

    public  function treatmentOption(){
        return $this->hasMany(TreatmentOption::class,'treatment_id','id');
    }

    public  function treatmentOptionPackage(){
        return $this->hasMany(TreatmentOptionPackage::class,'treatment_id','id');
    }
    
    public function category(){
        
        return $this->belongsTo(Category::class,'category_id');
    }
}
