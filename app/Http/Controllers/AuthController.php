<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

            // 👇 CEK STATUS AKUN DI SINI 👇
            if ($user->status === 'Pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Pesan formal untuk status Pending
                return back()->with('error', 'Akun Anda sedang dalam proses verifikasi oleh Admin. Harap menunggu.');
            } elseif ($user->status === 'Blacklist') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Pesan formal untuk status Blacklist
                return back()->with('error', 'Akun Anda telah diblokir. Anda tidak dapat mengakses sistem.');
            }

            // Kalau lolos dan statusnya Active, lanjut bikin session
            $request->session()->regenerate();
            
            // 👇 LOGIKA RBAC (Role-Based Access Control) 👇
            // Kalo yang login role-nya admin, lempar ke Dashboard Admin
            if ($user->role === 'admin') {
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
}