<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    // ----------------------------------------------------
    // HALAMAN UTAMA ORDERS / MY BOOKINGS
    // ----------------------------------------------------
    public function index()
    {
        $bookings = Booking::with('car')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('user_orders', compact('bookings'));
    }

    // ----------------------------------------------------
    // PROSES SIMPAN BOOKING DARI FORM CHECKOUT
    // ----------------------------------------------------
    public function store(Request $request, $slug)
    {
        // 1. Validasi Input (KTP atau Passport salah satu wajib ada)
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1',
            'renter_name' => 'required|string|max:255',
            'renter_phone' => 'required|string|max:20',
            'renter_id_number' => 'required|string|max:50',
            'pickup_time' => 'required',
            'doc_ktp' => 'required_without:doc_passport|image|mimes:jpeg,png,jpg|max:10240',
            'doc_sim' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'doc_passport' => 'required_without:doc_ktp|image|mimes:jpeg,png,jpg|max:10240',
            'doc_selfie' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'total_price' => 'required|numeric'
        ]);

        // 2. CEK STOK MOBIL (Inventory Management)
        $car = Car::findOrFail($request->car_id);
        if ($car->stock <= 0) {
            // Jika stok habis, kembalikan user ke katalog dengan pesan error
            return redirect()->route('user.catalog')->with('error', 'Waduh, keduluan! Mobil ' . $car->name . ' baru saja habis disewa pengguna lain. Silakan pilih mobil yang lain.');
        }

        try {
            // 3. Upload Dokumen ke Storage (Cek ketersediaan file)
            $ktpPath = null;
            if ($request->hasFile('doc_ktp')) {
                $ktpPath = $request->file('doc_ktp')->store('bookings', 'public');
            }

            $simPath = $request->file('doc_sim')->store('bookings', 'public');
            $selfiePath = $request->file('doc_selfie')->store('bookings', 'public');
            
            $passportPath = null;
            if ($request->hasFile('doc_passport')) {
                $passportPath = $request->file('doc_passport')->store('bookings', 'public');
            }

            // 4. Kalkulasi End Date
            $startDate = Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addDays($request->duration - 1); 

            // 5. Generate Kode Booking
            $bookingCode = 'ODR-' . strtoupper(uniqid());
            $withDriver = $request->has('with_driver') && $request->with_driver == 1 ? true : false;

            // 6. Simpan ke Database
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => Auth::id(),
                'car_id' => $request->car_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration' => $request->duration, 
                'total_price' => $request->total_price,
                'status' => 'Pending Payment', 
                'payment_status' => 'Unpaid', 
                'renter_name' => $request->renter_name,
                'renter_phone' => $request->renter_phone,
                'renter_id_number' => $request->renter_id_number,
                'pickup_time' => $request->pickup_time,
                'with_driver' => $withDriver,
                'doc_ktp' => $ktpPath,
                'doc_sim' => $simPath,
                'doc_passport' => $passportPath,
                'doc_selfie' => $selfiePath,
            ]);

            return redirect()->route('user.payment', $booking->id)->with('success', 'Booking berhasil dibuat! Silakan selesaikan pembayaran Anda.');

        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage())->withInput();
        }
    }

    // ----------------------------------------------------
    // PROSES KLIK "PAYMENT NOW" DI HALAMAN ORDERS
    // ----------------------------------------------------
    public function pay($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($booking->status == 'Pending Payment') {
            // Mengarahkan pengguna kembali ke halaman QRIS Midtrans
            return redirect()->route('user.payment', $booking->id);
        }

        return back()->with('error', 'Booking tidak valid untuk diproses.');
    }

    // ----------------------------------------------------
    // HALAMAN PAYMENT / INTEGRASI MIDTRANS QRIS
    // ----------------------------------------------------
    public function payment($id)
    {
        $booking = Booking::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if (!in_array($booking->status, ['Pending Payment', 'Ongoing'])) {
            return redirect()->route('user.orders')->with('error', 'Booking ini sudah tidak aktif atau dibatalkan.');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Persiapan parameter untuk dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                // Order ID ditambahkan fungsi time() agar selalu unik jika terjadi percobaan ulang
                'order_id' => $booking->booking_code . '-' . time(), 
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->renter_name,
                'email' => Auth::user()->email,
                'phone' => $booking->renter_phone,
            ],
            // Gunakan parameter resmi Midtrans untuk memunculkan QRIS
            'enabled_payments' => ['gopay', 'other_qris'], 
        ];

        // Meminta Snap Token ke API Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Meneruskan data booking beserta Snap Token ke tampilan antarmuka
        return view('user_payment', compact('booking', 'snapToken'));
    }

    // ----------------------------------------------------
    // CANCEL BOOKING OLEH USER
    // ----------------------------------------------------
    public function cancel($id)
    {
        $booking = Booking::with('car')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($booking->status == 'Pending Payment' || $booking->status == 'Ordered') {
            
            // LOGIKA INVENTORY: Jika user membatalkan pesanan yang sudah dibayar (stok sudah terpotong)
            if (in_array($booking->payment_status, ['Paid', 'Settlement'])) {
                $car = $booking->car;
                if ($car) {
                    $car->increment('stock'); // Kembalikan stok mobil
                    $car->update(['status' => 'Tersedia']); // Pastikan status kembali tersedia
                }
            }

            // Hanya ubah status booking, payment_status dibiarkan agar tidak terjadi bentrok constraint database
            $booking->update([
                'status' => 'Cancelled'
            ]);
            
            return back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return back()->with('error', 'Booking tidak dapat dibatalkan.');
    }
}