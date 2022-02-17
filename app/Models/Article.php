<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['img'];
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
