<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;       // 👈 Import DB Facade buat query otp_codes
use Illuminate\Support\Facades\Mail;     // 👈 Import Mail Facade
use App\Mail\SendOtpMail;                // 👈 Import Mailable Class
use Carbon\Carbon;                       // 👈 Import Carbon buat ngitung waktu expired

class AuthController extends Controller
{
    // Tampilin halaman Login
    public function showLogin()
    {
        return view('login');
    }

    // Proses data Login
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // 🟢 FIX: Ubah status jadi huruf kecil semua dan hapus spasi tersembunyi
            $status = strtolower(trim($user->status));

            // 👇 CEK STATUS AKUN DI SINI 👇
            if ($status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Pesan formal untuk status Pending
                return redirect()->route('login')->with('error', 'Akun Anda sedang dalam proses verifikasi oleh Admin. Harap menunggu.');
                
            } elseif ($status === 'blacklist') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Pesan formal untuk status Blacklist
                return redirect()->route('login')->with('error', 'Akun Anda telah diblokir. Anda tidak dapat mengakses sistem.');
            }

            // Kalau lolos dan statusnya Active, lanjut bikin session
            $request->session()->regenerate();
            
            // 👇 LOGIKA RBAC (Role-Based Access Control) 👇
            // 🟢 FIX: Ubah role jadi huruf kecil semua biar gak bocor gara-gara huruf besar/kecil
            $role = strtolower(trim($user->role));

            // Kalo yang login role-nya admin, lempar ke Dashboard Admin
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // Kalo user biasa, lempar ke Dashboard User
            return redirect()->route('dashboard'); 
        }

        // 👇 PESAN ERROR FORMAL 👇
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Tampilin halaman Register
    public function showRegister()
    {
        return view('register');
    }

    // Proses simpan data Register
    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nomor_hp' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => Hash::make($request->password), 
            // Note: role otomatis jadi 'user' dan status otomatis 'Pending' dari migration database
        ]);

        // Pesan sukses formal
        return redirect()->route('login')->with('success', 'Registrasi berhasil. Akun Anda sedang menunggu verifikasi dari Admin.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Habis logout, balikin dia ke landing page (home)
        return redirect()->route('home');
    }

    // ==========================================
    // FUNGSI UNTUK RESET PASSWORD VIA OTP
    // ==========================================

    // 1. Fungsi Kirim OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999); // Generate 6 digit angka random
        
        // Simpan atau update ke tabel otp_codes (biar ga numpuk datanya)
        DB::table('otp_codes')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => $otp, 
                'expires_at' => Carbon::now()->addMinutes(10), // Expired dalam 10 menit
                'created_at' => now(), 
                'updated_at' => now()
            ]
        );

        // Kirim email pake Mailtrap / SMTP
        Mail::to($request->email)->send(new SendOtpMail($otp));

        return response()->json(['success' => true, 'message' => 'OTP berhasil dikirim ke email.']);
    }

    // 2. Fungsi Validasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email', 
            'otp' => 'required|string'
        ]);

        $record = DB::table('otp_codes')
                    ->where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->first();

        // Cek kalau kode OTP nya gak ada ATAU udah lebih dari 10 menit (expired)
        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah atau sudah kedaluwarsa.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'OTP valid.']);
    }

    // 3. Fungsi Simpan Password Baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed' // Harus sama dengan form input konfirmasi password
        ]);

        // Ganti password di tabel users
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);
        
        // Hapus jejak OTP biar aman dan gak disalahgunain lagi
        DB::table('otp_codes')->where('email', $request->email)->delete(); 

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah!']);
    }
}