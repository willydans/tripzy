<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    // 1. Tampilin Semua User & Statistik
    public function index()
    {
        // withCount('bookings') bakal otomatis bikin variabel 'bookings_count'
        $users = User::withCount('bookings')->orderBy('created_at', 'desc')->get();
        
        // Hitung statistik buat card informasi di atas
        $kycPending = $users->where('status', 'Pending')->count();
        $blacklistCount = $users->where('status', 'Blacklist')->count();

        // Sesuaiin nama view-nya dengan struktur folder lu (contoh: admin.admin_users atau admin.users.index)
        return view('admin.admin_users', compact('users', 'kycPending', 'blacklistCount'));
    }

    // 2. Proses Simpan User / Admin Baru (Create)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:Active,Pending,Blacklist',
            'nomor_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'jenis_sim' => 'nullable|string',
            'nomor_sim' => 'nullable|string',
            'masa_berlaku_sim' => 'nullable|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Wajib di-hash biar aman
            'role' => $request->role,
            'status' => $request->status,
            'nomor_hp' => $request->nomor_hp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'jenis_sim' => $request->jenis_sim,
            'nomor_sim' => $request->nomor_sim,
            'masa_berlaku_sim' => $request->masa_berlaku_sim,
        ]);

        return back()->with('custom_toast', 'User baru berhasil ditambahkan!');
    }

    // 3. Proses Update Data User / Admin (Edit)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6', // Nullable karena kalau kosong gak bakal ganti pw
            'role' => 'required|in:user,admin',
            'status' => 'required|in:Active,Pending,Blacklist',
            'nomor_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'jenis_sim' => 'nullable|string',
            'nomor_sim' => 'nullable|string',
            'masa_berlaku_sim' => 'nullable|date',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'nomor_hp' => $request->nomor_hp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'jenis_sim' => $request->jenis_sim,
            'nomor_sim' => $request->nomor_sim,
            'masa_berlaku_sim' => $request->masa_berlaku_sim,
        ];

        // Logika ganti password: Cuma diupdate jika form input password diisi oleh admin
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('custom_toast', 'Data ' . $user->name . ' berhasil diperbarui!');
    }

    // 4. Verifikasi Akun (KYC)
    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'Active']);
        
        return back()->with('custom_toast', 'Account Verification successfully');
    }

    // 5. Masukkan User ke Blacklist
    public function blacklist($id)
    {
        $user = User::findOrFail($id);

        // Pencegahan: Cek jangan sampai mem-blacklist diri sendiri atau sesama admin
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Aksi ditolak! Anda tidak bisa mem-blacklist akun Anda sendiri.');
        }
        if ($user->role === 'admin') {
            return back()->with('error', 'Aksi ditolak! Anda tidak bisa mem-blacklist sesama Admin.');
        }

        $user->update(['status' => 'Blacklist']);
        
        return back()->with('custom_toast', $user->name . ' was successfully added to the blacklist');
    }

    // 6. Cabut Status Blacklist (Unblacklist)
    public function unblacklist($id)
    {
        $user = User::findOrFail($id);
        
        $user->update(['status' => 'Active']); // Kembalikan ke status aktif biasa
        
        return back()->with('custom_toast', 'Akses akun ' . $user->name . ' berhasil dipulihkan!');
    }

    // 7. Hapus User Selamanya (Delete)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Pencegahan: Cek biar nggak menghapus diri sendiri yang lagi login
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Aksi ditolak! Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('custom_toast', 'Akun pengguna berhasil dihapus permanen.');
    }
}