<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Cast 'facilities' jadi Array biar gampang di-looping di Blade
    protected $casts = [
        'facilities' => 'array', 
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}