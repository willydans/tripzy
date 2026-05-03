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
            'payment_status' => 'Paid' // Kalo diverifikasi, otomatis dianggap lunas
        ]);
        return back()->with('success', 'Booking berhasil diverifikasi!');
    }

    // Fungsi klik tombol "Confirmation of Pickup"
    public function pickup(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Cek apakah kode booking yang diketik admin sesuai
        if ($request->booking_code !== $booking->booking_code) {
            return back()->with('error', 'Kode Booking tidak cocok!');
        }

        $booking->update(['status' => 'Ongoing']);
        $booking->car->update(['status' => 'Disewa']); // Status mobil otomatis jadi 'Disewa'

        return back()->with('success', 'Mobil berhasil diambil wisatawan!');
    }

    // Fungsi klik tombol "Return Confirmation"
    public function returnCar($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'History']);
        $booking->car->update(['status' => 'Tersedia']); // Status mobil balik jadi 'Tersedia'

        return back()->with('success', 'Mobil berhasil dikembalikan!');
    }

    // Fungsi klik tombol "Cancel"
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Cancelled']);
        
        return back()->with('success', 'Booking berhasil dibatalkan!');
    }
}