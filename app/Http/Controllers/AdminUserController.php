<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        // Ambil semua user (bisa di-filter khusus role 'user' kalau lu punya sistem role)
        // withCount('bookings') bakal otomatis bikin variabel 'bookings_count'
        $users = User::withCount('bookings')->orderBy('created_at', 'desc')->get();
        
        // Hitung statistik buat tombol di atas
        $kycPending = $users->where('status', 'Pending')->count();
        $blacklistCount = $users->where('status', 'Blacklist')->count();

        return view('admin.admin_users', compact('users', 'kycPending', 'blacklistCount'));
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'Active']);
        
        return back()->with('custom_toast', 'Account Verification successfully');
    }

    public function blacklist($id)
    {
        $user = User::findOrFail($id);

        // 👇 CEGAH ADMIN DI-BLACKLIST 👇
        if ($user->role === 'admin') {
            return back()->with('error', 'Aksi ditolak! Anda tidak bisa mem-blacklist sesama Admin.');
        }

        $user->update(['status' => 'Blacklist']);
        
        return back()->with('custom_toast', $user->name . ' was successfully added to the blacklist');
    }
}