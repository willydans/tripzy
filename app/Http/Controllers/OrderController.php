<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Tarik semua booking milik user yang lagi login, diurutin dari yang terbaru
        $bookings = Booking::with('car')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('user_orders', compact('bookings'));
    }

    // Fungsi buat Cancel Booking oleh User (hanya bisa kalau masih Pending Payment)
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($booking->status == 'Pending Payment' || $booking->status == 'Ordered') {
            $booking->update(['status' => 'Cancelled']);
            return back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return back()->with('error', 'Booking tidak bisa dibatalkan.');
    }
}