<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Data Summary Cards
        $totalFleet = Car::count();
        $activeRentals = Booking::where('status', 'Ongoing')->count();
        $pendingVerification = Booking::where('status', 'Ordered')->count();
        
        // Total Pendapatan dari booking yang udah dibayar/selesai
        $totalRevenue = Booking::whereIn('status', ['Ongoing', 'History'])->sum('total_price');

        // 2. Data List Active Rentals (Ongoing)
        $activeRentalsList = Booking::with(['user', 'car'])->where('status', 'Ongoing')->latest()->take(4)->get();

        // 3. Data List New Transactions (5 Transaksi Terakhir)
        $newTransactions = Booking::with(['user', 'car'])->latest()->take(5)->get();

        // 4. Data Status Fleet (Stok Mobil)
        $statusFleet = Car::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalFleet', 'activeRentals', 'pendingVerification', 'totalRevenue',
            'activeRentalsList', 'newTransactions', 'statusFleet'
        ));
    }
}