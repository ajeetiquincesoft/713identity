<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public  function appointmentPackages()
    {
        return $this->hasMany(AppointmentPackages::class, 'appointment_id', 'id');
    }
    public  function appointmentPayment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatment_id');
    }
}
