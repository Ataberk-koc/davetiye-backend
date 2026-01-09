<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'image_path',
        'caption',
        'is_approved'
    ];

    // Bir anı, bir davetiyeye aittir
    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}