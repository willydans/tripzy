<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 👈 Wajib tambah ini buat ngatur file

class ProfileController extends Controller
{
    // Tampilkan Halaman Profil
    public function edit()
    {
        $user = Auth::user();
        return view('user_profile', compact('user'));
    }

    // Proses Update Profil & Foto
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi inputan (Kalau gagal, bakal dilempar balik ke halaman profil)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'nomor_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'kota' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'nomor_sim' => 'nullable|string|max:50',
            'jenis_sim' => 'nullable|string|max:50',
            'masa_berlaku_sim' => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        try {
            $data = $request->all();

            // Cek kalau user upload foto baru
            if ($request->hasFile('profile_photo')) {
                // Hapus foto lama dari storage kalau sebelumnya udah pernah upload
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                
                // Simpan foto baru ke folder 'profile_photos' di storage/app/public/
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $data['profile_photo'] = $path;
            }

            $user->update($data);
            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile!');
        }
    }
}