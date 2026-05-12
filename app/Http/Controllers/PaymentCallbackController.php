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

        // 3. Mencari data pesanan di dalam database
        $booking = Booking::where('booking_code', $bookingCode)->first();

        // Jika pesanan tidak ditemukan, kembalikan response error agar Midtrans tahu
        if (!$booking) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // 4. Logika Pembaruan Status Otomatis
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'challenge') {
                // Jika pembayaran mencurigakan dan ditahan oleh fraud detection system Midtrans
                $booking->update([
                    'payment_status' => 'Pending',
                ]);
            } else {
                // Jika pembayaran berhasil dan terverifikasi
                $booking->update([
                    'status' => 'Ongoing',
                    'payment_status' => 'Paid',
                ]);
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