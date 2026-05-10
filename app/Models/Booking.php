<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi massal kecuali 'id' (Nggak perlu pakai $fillable lagi)
    protected $guarded = ['id'];

    // Casting agar tipe data gampang dikelola oleh Laravel
    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'duration'    => 'integer', // 👈 Tambahan biar dijamin masuk sbg angka
        'total_price' => 'integer', // 👈 Tambahan biar dijamin masuk sbg angka
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