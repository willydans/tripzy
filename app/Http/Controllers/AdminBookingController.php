<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        // Narik semua data booking beserta relasi data mobilnya
        $bookings = Booking::with('car')->orderBy('created_at', 'desc')->get();

        // Hitung data untuk Card Summary di atas tabel
        $totalBookings = Booking::count();
        $verificationRequired = Booking::where('status', 'Ordered')->whereNull('verified_at')->count();
        $verified = Booking::where('status', 'Ordered')->whereNotNull('verified_at')->count();
        $activeRentals = Booking::where('status', 'Ongoing')->count();
        $awaitingPayment = Booking::where('status', 'Pending Payment')->count();

        return view('admin.booking_verification', compact(
            'bookings', 'totalBookings', 'verificationRequired', 'verified', 'activeRentals', 'awaitingPayment'
        ));
    }

    // Fungsi klik tombol "Verification"
    public function verify($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'verified_at' => now(),
            'payment_status' => 'Paid' // Kalo diverifikasi dokumen, otomatis dianggap lunas
        ]);

        return back()->with('success', 'Booking berhasil diverifikasi!');
    }

    // Fungsi klik tombol "Confirmation of Pickup"
    public function pickup(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Cek apakah kode booking yang diketik admin sesuai dengan yang di database
        if ($request->booking_code !== $booking->booking_code) {
            return back()->with('error', 'Kode Booking tidak cocok!');
        }

        $booking->update(['status' => 'Ongoing']);
        
        // Cek stok mobil, kalau setelah diambil stoknya jadi 0, ubah status ke 'Disewa'
        if ($booking->car->stock <= 0) {
            $booking->car->update(['status' => 'Disewa']);
        }

        return back()->with('success', 'Mobil berhasil diambil wisatawan!');
    }

    // Fungsi klik tombol "Return Confirmation" (Update untuk nyimpen data inspeksi)
    public function returnCar(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // 1. Update status Booking dan simpan data inspeksi dari Form
        $booking->update([
            'status'             => 'History',
            
            // Data Kondisi & Catatan
            'body_condition'     => $request->body_condition,
            'body_notes'         => $request->body_notes,
            'interior_condition' => $request->interior_condition,
            'interior_notes'     => $request->interior_notes,
            
            // Checkbox & Kondisi Ban
            'chk_scratches'      => $request->has('chk_scratches'),
            'chk_lights'         => $request->has('chk_lights'),
            'chk_toolkit'        => $request->has('chk_toolkit'),
            'chk_sparetire'      => $request->has('chk_sparetire'),
            'tire_condition'     => $request->tire_condition,
            
            // Jarak Tempuh, Keterlambatan, & Denda
            'mileage'            => $request->mileage,
            'delay_hours'        => $request->delay_hours ?? 0,
            'damage_fine'        => $request->damage_fine ?? 0,
            'late_fine'          => $request->late_fine ?? 0,
            
            // Catatan Umum
            'general_notes'      => $request->general_notes,
        ]);

        // 2. Balikin stok mobil (Inventory Management)
        $car = $booking->car;
        if ($car) {
            $car->increment('stock'); 
            // Pastikan status mobil balik jadi 'Tersedia' karena stok sudah bertambah
            $car->update(['status' => 'Tersedia']); 
        }

        return back()->with('success', 'Mobil berhasil dikembalikan & Data inspeksi disimpan!');
    }

    // Fungsi klik tombol "Cancel"
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Jika booking dibatalkan padahal sudah lunas (stok sudah terpotong oleh Midtrans), kembalikan stoknya
        if (in_array($booking->payment_status, ['Paid', 'Settlement'])) {
            $car = $booking->car;
            if ($car) {
                $car->increment('stock');
                $car->update(['status' => 'Tersedia']);
            }
        }

        $booking->update([
            'status' => 'Cancelled',
            'payment_status' => 'Cancelled'
        ]);
        
        return back()->with('success', 'Booking berhasil dibatalkan!');
    }
}