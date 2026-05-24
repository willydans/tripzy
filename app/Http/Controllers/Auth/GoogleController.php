<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    // Arahkan ke Google untuk login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle balasan dari Google
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user udah ada di database berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Kalau user belum ada, daftarin otomatis
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()), // Password random, karena loginnya via Google
                    // Role otomatis 'user' dan status otomatis 'Pending' dari database/migration
                ]);
            }

            // 🟢 FIX: Cek status akun kayak di AuthController
            $status = strtolower(trim($user->status));

            if ($status === 'pending') {
                // Jangan loginin, suruh nunggu verifikasi
                return redirect()->route('login')->with('error', 'Akun Google Anda sedang dalam proses verifikasi oleh Admin. Harap menunggu.');
                
            } elseif ($status === 'blacklist') {
                // Jangan loginin, akun diblokir
                return redirect()->route('login')->with('error', 'Akun Google Anda telah diblokir. Anda tidak dapat mengakses sistem.');
            }

            // Kalau lolos, berarti statusnya Active. Langsung login-kan
            Auth::login($user);
            $request->session()->regenerate();

            // 🟢 FIX: Cek role akun secara kebal huruf besar/kecil
            $role = strtolower(trim($user->role));

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // Kalau misal orangnya batalin login google, atau error koneksi
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}