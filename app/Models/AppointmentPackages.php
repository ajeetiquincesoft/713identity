<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPackages extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function appointment(){
        
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
    public  function treatmentOptionPackage(){
        return $this->belongsTo(TreatmentOptionPackage::class,'treatmentoptionpackage_id');
    }
}
