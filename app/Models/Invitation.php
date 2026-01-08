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
    ];

    protected $casts = [
        'wedding_date' => 'datetime',
    ];
}
