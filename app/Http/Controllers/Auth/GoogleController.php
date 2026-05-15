<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Arahin user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Tangkep balikan dari Google
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user dengan email ini udah ada di database
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update google_id kalau sebelumnya dia daftar manual (belum punya google_id)
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }

                // 🟢 LOGIKANYA DI SINI: Cek apakah akun sudah diaktivasi admin
                // (Sesuaikan 'status' dan 'Pending' dengan nama kolom aktivasi di database lu)
                if ($user->status == 'Pending') { 
                    return redirect()->route('login')->with('error', 'Akun Anda masih menunggu aktivasi dari admin Tripzy.');
                }

                // Kalau udah aktif, langsung loginin
                Auth::login($user);
                return redirect()->intended('/dashboard');

            } else {
                // 🟢 USER BARU: Daftar otomatis tapi statusnya dibuat Pending / Belum Aktif
                $newUser = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    // Kasih password random karena dia login via Google
                    'password'  => bcrypt(Str::random(16)), 
                    // Set status awal nunggu aktivasi
                    'status'    => 'Pending' 
                ]);

                // Jangan diloginin, tapi balikin ke halaman login + pesen sukses daftar
                return redirect()->route('login')->with('success', 'Pendaftaran via Google berhasil! Silakan tunggu admin mengaktifkan akun Anda.');
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }
}