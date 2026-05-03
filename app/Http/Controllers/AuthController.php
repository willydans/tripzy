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
            $request->session()->regenerate();
            
            // 👇 LOGIKA RBAC (Role-Based Access Control) 👇
            // Kalo yang login role-nya admin, lempar ke Dashboard Admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // Kalo user biasa, lempar ke Dashboard User
            return redirect()->route('dashboard'); 
        }

        return back()->withErrors([
            'email' => 'Email atau password salah bre.',
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
            'password' => Hash::make($request->password), // Password wajib di-hash
            // Note: role otomatis jadi 'user' karena kita udah set default 'user' di migration database
        ]);

        // Kalo sukses register, lempar ke halaman login
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // 👇 Habis logout, balikin dia ke landing page (home)
        return redirect()->route('home');
    }
}