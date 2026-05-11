<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Booking; // 👈 Import relasi Booking

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_hp',
        'tanggal_lahir',
        'jenis_kelamin',
        'role',
        'kota',
        'alamat',
        'nomor_sim',
        'jenis_sim',
        'masa_berlaku_sim',
        'profile_photo',
        'status', // 👈 Tambahan field status biar bisa diupdate
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'masa_berlaku_sim' => 'date',
    ];

    // 👈 Tambahan fungsi relasi: Satu user bisa punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 👈 TAMBAHAN FUNGSI BOOT: Biar Admin otomatis Active tanpa nunggu verifikasi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Kalau role-nya admin, paksa statusnya jadi Active dari awal
            if ($user->role === 'admin') {
                $user->status = 'Active';
            }
        });
    }
}