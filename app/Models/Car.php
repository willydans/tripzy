<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi kecuali ID
    protected $guarded = ['id'];

    // Cast tipe data biar gampang diolah
    protected $casts = [
        'facilities'   => 'array',   // Cast json dari DB jadi Array
        'stock'        => 'integer', // Pastikan stock terbaca sebagai angka
        'service_date' => 'date',    // Otomatis ubah jadi object Carbon (biar bisa ->format('Y-m-d'))
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}