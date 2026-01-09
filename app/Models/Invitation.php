<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'groom_name',
        'groom_surname',
        'bride_name',
        'bride_surname',
        'wedding_date',
        'image',
        'event_type',
        'location',
        'description',
        'map_url',
    ];

    protected $casts = [
        'wedding_date' => 'datetime',
    ];
    // Bir davetiyenin birden fazla anısı (fotoğrafı) olabilir
    public function moments()
    {
        return $this->hasMany(Moment::class);
    }
    public function border()
    {
        return $this->belongsTo(Border::class);
    }
}
