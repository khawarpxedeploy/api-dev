<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    public function getImageAttribute($value)
    {
        if($value){
           $value = Storage::url($value);
        }
        return $value;
    }
}
