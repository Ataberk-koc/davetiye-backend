<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Border extends Model
{
    protected $fillable = ['name', 'image_path'];

    // Frontend için tam URL döndüren bir özellik ekleyelim
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}