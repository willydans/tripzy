<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            'doc_ktp' => 'required_without:doc_passport|image|mimes:jpeg,png,jpg|max:10240', // 👈 UBAH DISINI
            'doc_sim' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'doc_passport' => 'required_without:doc_ktp|image|mimes:jpeg,png,jpg|max:10240', // 👈 UBAH DISINI
            'doc_selfie' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'total_price' => 'required|numeric'
        ]);

        try {
            // 2. Upload Dokumen ke Storage (Cek dulu ada filenya atau nggak)
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

            // 3. Kalkulasi End Date
            $startDate = Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addDays($request->duration - 1); 

            // 4. Generate Kode Booking
            $bookingCode = 'ODR-' . strtoupper(uniqid());
            $withDriver = $request->has('with_driver') && $request->with_driver == 1 ? true : false;

            // 5. Simpan ke Database
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

            return redirect()->route('user.payment', $booking->id)->with('success', 'Booking created successfully! Please complete your payment.');

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
            $booking->update([
                'status' => 'Ongoing',
                'payment_status' => 'Paid' 
            ]);
            
            return redirect()->route('user.payment', $booking->id)->with('success', 'Payment confirmed! Status is now Ongoing and Paid.');
        }

        return back()->with('error', 'Booking tidak valid untuk diproses.');
    }

    // ----------------------------------------------------
    // HALAMAN PAYMENT / QRIS
    // ----------------------------------------------------
    public function payment($id)
    {
        $booking = Booking::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if (!in_array($booking->status, ['Pending Payment', 'Ongoing'])) {
            return redirect()->route('user.orders')->with('error', 'Booking ini sudah tidak aktif atau dibatalkan.');
        }

        return view('user_payment', compact('booking'));
    }

    // ----------------------------------------------------
    // CANCEL BOOKING OLEH USER
    // ----------------------------------------------------
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($booking->status == 'Pending Payment' || $booking->status == 'Ordered') {
            $booking->update([
                'status' => 'Cancelled',
                'payment_status' => 'Cancelled'
            ]);
            return back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return back()->with('error', 'Booking tidak bisa dibatalkan.');
    }
}