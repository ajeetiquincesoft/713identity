<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class TreatmentOption extends Model
{
    use HasFactory;
	protected $appends=['img'];
    public function treatment(){
        return $this->belongsTo(Treatment::class,'treatment');
    }
    public  function treatmentOptionPackage(){
        return $this->hasMany(TreatmentOptionPackage::class,'treatmentoption_id','id');
    }
	function upload_image($images)
    {
       
        if ($images) {
            $disk = Storage::disk("public");
            $disk->delete($this->image);          
            $new_path = $disk->putFile("", $images);
            $this->image = $new_path;
            return $new_path;
        }
    }
    function getImgAttribute()
    {
        $image = Storage::disk('public')->url($this->image);
        if ($image) {
            return $image;
        }

        return asset('assets/default.jpg');
    }
}
