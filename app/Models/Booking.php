<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Casting agar tipe data gampang dikelola oleh Laravel (contoh pakai fungsi format() tanggal)
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'with_driver' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relasi balik ke tabel Users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi balik ke tabel Cars
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}