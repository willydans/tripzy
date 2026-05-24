<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FonnteService; // 🟢 FIX: Import Service Fonnte untuk notifikasi WA

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('car')->orderBy('created_at', 'desc')->get();

        $totalBookings = Booking::count();
        $verificationRequired = Booking::where('status', 'Ordered')->whereNull('verified_at')->count();
        $verified = Booking::where('status', 'Ordered')->whereNotNull('verified_at')->count();
        $activeRentals = Booking::where('status', 'Ongoing')->count();
        $awaitingPayment = Booking::where('status', 'Pending Payment')->count();

        return view('admin.booking_verification', compact(
            'bookings', 'totalBookings', 'verificationRequired', 'verified', 'activeRentals', 'awaitingPayment'
        ));
    }

    public function verify($id)
    {
        // Panggil beserta data car-nya biar gampang diambil namanya
        $booking = Booking::with('car')->findOrFail($id);
        
        $booking->update([
            'verified_at' => now(),
            'payment_status' => 'Paid' // Pastikan 'Paid' sesuai dengan ENUM/Constraint di DB
        ]);

        // 🟢 INTEGRASI NOTIFIKASI WA: Verifikasi Berhasil 🟢
        $waMessage = "Hore! 🎉\n"
                   . "Pembayaran untuk pesanan *{$booking->booking_code}* telah *DIVERIFIKASI* oleh Admin Tripzy.\n\n"
                   . "Mobil *{$booking->car->name}* sudah disiapkan. Silakan ambil mobil pada jadwal yang ditentukan:\n"
                   . "Tanggal : *{$booking->start_date->format('d M Y')}*\n"
                   . "Jam Pick-up : *{$booking->pickup_time}*\n\n"
                   . "Hati-hati di jalan dan selamat menikmati perjalanan Anda!";
        
        FonnteService::sendWA($booking->renter_phone, $waMessage);

        return back()->with('success', 'Booking berhasil diverifikasi!');
    }

    public function pickup(Request $request, $id)
    {
        $booking = Booking::with('car')->findOrFail($id);
        
        if ($request->booking_code !== $booking->booking_code) {
            return back()->with('error', 'Kode Booking tidak cocok!');
        }

        // Cek stok sebelum proses pickup
        if ($booking->car->stock <= 0) {
            return back()->with('error', 'Stok mobil tidak tersedia!');
        }

        $booking->update(['status' => 'Ongoing']);
        
        // Kurangi stok jika belum dilakukan
        $booking->car->decrement('stock');
        
        if ($booking->car->fresh()->stock <= 0) {
            $booking->car->update(['status' => 'Disewa']);
        }

        // 🟢 INTEGRASI NOTIFIKASI WA: Konfirmasi Pick-Up (Sedang Disewa) 🟢
        $waMessage = "Halo *{$booking->renter_name}*,\n\n"
                   . "Mobil *{$booking->car->name}* dengan plat nomor *{$booking->car->license_plate}* telah berhasil diambil. "
                   . "Status pesanan Anda saat ini adalah *ONGOING* (Aktif). 🚗💨\n\n"
                   . "Pastikan untuk mengembalikan mobil tepat waktu pada *{$booking->end_date->format('d M Y')}* untuk menghindari denda keterlambatan.\n"
                   . "Selamat menikmati perjalanan Anda bersama Tripzy!";
        
        FonnteService::sendWA($booking->renter_phone, $waMessage);

        return back()->with('success', 'Mobil berhasil diambil wisatawan!');
    }

    public function returnCar(Request $request, $id)
    {
        $booking = Booking::with('car')->findOrFail($id);
        
        $booking->update([
            'status'             => 'History',
            'body_condition'     => $request->body_condition,
            'body_notes'         => $request->body_notes,
            'interior_condition' => $request->interior_condition,
            'interior_notes'     => $request->interior_notes,
            'chk_scratches'      => $request->has('chk_scratches'),
            'chk_lights'         => $request->has('chk_lights'),
            'chk_toolkit'        => $request->has('chk_toolkit'),
            'chk_sparetire'      => $request->has('chk_sparetire'),
            'tire_condition'     => $request->tire_condition,
            'mileage'            => $request->mileage,
            'delay_hours'        => $request->delay_hours ?? 0,
            'damage_fine'        => $request->damage_fine ?? 0,
            'late_fine'          => $request->late_fine ?? 0,
            'general_notes'      => $request->general_notes,
        ]);

        $car = $booking->car;
        if ($car) {
            $car->increment('stock'); 
            $car->update(['status' => 'Tersedia']); 
        }

        // 🟢 INTEGRASI NOTIFIKASI WA: Konfirmasi Pengembalian 🟢
        $totalFine = ($request->damage_fine ?? 0) + ($request->late_fine ?? 0);
        $fineText = $totalFine > 0 ? "Total Denda Tambahan : *Rp " . number_format($totalFine, 0, ',', '.') . "*\n\n" : "\n";

        $waMessage = "Halo *{$booking->renter_name}*,\n\n"
                   . "Mobil *{$booking->car->name}* untuk pesanan *{$booking->booking_code}* telah *BERHASIL DIKEMBALIKAN*. ✅\n\n"
                   . $fineText
                   . "Terima kasih telah mempercayakan perjalanan Anda bersama Tripzy. Kami tunggu pesanan Anda selanjutnya! ✨";
        
        FonnteService::sendWA($booking->renter_phone, $waMessage);

        return back()->with('success', 'Mobil berhasil dikembalikan & Data inspeksi disimpan!');
    }

    public function cancel($id)
    {
        $booking = Booking::with('car')->findOrFail($id);
        
        // Simpan status lama sebelum diupdate buat ngecek logika refund inventory
        $oldStatus = $booking->status;

        $booking->update([
            'status' => 'Cancelled',
            'payment_status' => 'Unpaid' 
        ]);
        
        // Jika status sebelumnya sudah Ongoing (artinya udah di-pickup/dibayar), kembalikan stok
        if ($oldStatus === 'Ongoing') {
            $booking->car->increment('stock');
            $booking->car->update(['status' => 'Tersedia']);
        }
        
        // 🟢 INTEGRASI NOTIFIKASI WA: Batal dari Admin 🟢
        $waMessage = "Mohon maaf *{$booking->renter_name}*,\n\n"
                   . "Pesanan Tripzy Anda dengan kode *{$booking->booking_code}* telah *DIBATALKAN* oleh Admin.\n\n"
                   . "Jika Anda merasa ini adalah kesalahan atau membutuhkan informasi lebih lanjut, silakan hubungi Customer Service kami.";
        
        FonnteService::sendWA($booking->renter_phone, $waMessage);

        return back()->with('success', 'Booking berhasil dibatalkan!');
    }
}