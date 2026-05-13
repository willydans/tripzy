<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // 1. Menangkap data (payload) yang dikirim oleh Midtrans
        $payload = $request->all();

        // Menyimpan log payload untuk keperluan debugging jika terjadi kendala (opsional)
        Log::info('Midtrans Webhook Payload: ', $payload);

        $transactionStatus = $payload['transaction_status'];
        $orderIdFull = $payload['order_id']; // Contoh nilai yang masuk: ODR-64ABCD-1681234567
        $fraudStatus = $payload['fraud_status'] ?? '';

        // 2. Mengekstrak booking_code asli
        // Karena sebelumnya kita gabungkan dengan time(), kita pisahkan menggunakan explode
        $orderIdParts = explode('-', $orderIdFull);
        
        // Menggabungkan kembali bagian "ODR" dan kode uniknya (tanpa time)
        $bookingCode = $orderIdParts[0] . '-' . $orderIdParts[1]; 

        // 3. Mencari data pesanan di dalam database (di-load bareng relasi 'car')
        $booking = Booking::with('car')->where('booking_code', $bookingCode)->first();

        // Jika pesanan tidak ditemukan, kembalikan response error agar Midtrans tahu
        if (!$booking) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // 4. Logika Pembaruan Status Otomatis & Manajemen Stok Mobil
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'challenge') {
                // Jika pembayaran mencurigakan dan ditahan oleh fraud detection system Midtrans
                $booking->update([
                    'payment_status' => 'Pending',
                ]);
            } else {
                // SAFETY CHECK: Pastikan booking belum berstatus Paid biar stok gak kepotong 2x
                // jika Midtrans mengirimkan notifikasi lebih dari sekali.
                if ($booking->payment_status != 'Paid') {
                    
                    // Jika pembayaran berhasil dan terverifikasi
                    $booking->update([
                        'status' => 'Ongoing',
                        'payment_status' => 'Paid',
                    ]);

                    // --- LOGIKA INVENTORY / STOK ---
                    $car = $booking->car;
                    if ($car && $car->stock > 0) {
                        // Kurangi jumlah stok 1
                        $car->decrement('stock');
                        
                        // Kalau setelah dikurangi ternyata stoknya habis (0), otomatis ubah status mobil jadi 'Disewa'
                        if ($car->stock <= 0) {
                            $car->update(['status' => 'Disewa']);
                        }
                    }
                }
            }
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // Jika pembayaran dibatalkan pengguna, ditolak, atau kedaluwarsa (lewat batas waktu QRIS)
            $booking->update([
                'status' => 'Cancelled',
                'payment_status' => 'Cancelled',
            ]);
        } else if ($transactionStatus == 'pending') {
            // Jika pengguna baru membuka QRIS namun belum memindai
            $booking->update([
                'payment_status' => 'Unpaid',
            ]);
        }

        // 5. Kembalikan response 200 OK agar Midtrans berhenti mengirim notifikasi ulang
        return response()->json(['message' => 'Notifikasi berhasil diproses']);
    }
}