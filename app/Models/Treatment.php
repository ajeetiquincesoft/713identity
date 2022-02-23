<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Treatment extends Model
{
    use HasFactory;
    protected $guarded = [];
	protected $appends=['totalprice','wishlist','img'];

    public  function treatmentOption(){
        return $this->hasMany(TreatmentOption::class,'treatment_id','id');
    }

    public  function treatmentOptionPackage(){
        return $this->hasMany(TreatmentOptionPackage::class,'treatment_id','id');
    }
    
    public function category(){
        
        return $this->belongsTo(Category::class,'category_id');
    }

 

    public function getTotalPriceAttribute(){
        $total_price_of_packages=$this->treatmentOptionPackage->sum('price');
        return $total_price_of_packages;
    }
    public function getWishlistAttribute(){
        $user = auth('api')->authenticate();

        $whishlist = Wishlist::whereUserId($user->id)->whereTreatmentId($this->id)->first();
        
        if($whishlist)
        return true;

        return false;
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
