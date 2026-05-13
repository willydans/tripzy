<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function show($slug)
    {
        // Cari data mobil berdasarkan slug di database
        $car = Car::where('slug', $slug)->firstOrFail();
        
        // LOGIKA INVENTORY: Cek apakah stok mobil masih tersedia
        if ($car->stock <= 0) {
            // Jika stok habis (0), kembalikan user ke halaman sebelumnya dengan pesan error.
            // Gw pake redirect()->back() biar fleksibel, fallback-nya ke user.catalog
            return redirect()->back()->with('error', 'Mohon maaf, stok unit mobil ' . $car->name . ' sedang kosong atau disewa semua.');
        }

        // Jika stok aman, tampilkan halaman detail mobil (sesuaikan nama view lu, misal: car_detail atau user_car_detail)
        return view('car_detail', compact('car'));
    }
}