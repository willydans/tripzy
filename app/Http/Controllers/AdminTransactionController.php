<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('car')->orderBy('created_at', 'desc')->get();
        $totalRevenue = Booking::where('payment_status', 'Paid')->sum('total_price');
        $totalTransactions = $bookings->count();
        $pending = $bookings->whereIn('status', ['Pending Payment', 'Ordered'])->count();
        $successful = $bookings->whereIn('status', ['Ongoing', 'History'])->count();
        $cancelled = $bookings->where('status', 'Cancelled')->count();
        $refunded = 0;

        return view('admin.admin_transactions', compact(
            'bookings', 'totalRevenue', 'totalTransactions', 
            'pending', 'successful', 'cancelled', 'refunded'
        ));
    }

    // FUNGSI EXPORT REPORT CSV
    public function export()
    {
        $bookings = Booking::with('car')->orderBy('created_at', 'desc')->get();
        $fileName = 'Tripzy_Transaction_Report_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Booking', 'Customer', 'Phone', 'Car', 'License Plate', 'Start Date', 'End Date', 'Total Price', 'Status', 'Payment'];

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->renter_name,
                    $booking->renter_phone,
                    $booking->car->name,
                    $booking->car->license_plate,
                    $booking->start_date->format('Y-m-d'),
                    $booking->end_date->format('Y-m-d'),
                    'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                    $booking->status,
                    $booking->payment_status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}