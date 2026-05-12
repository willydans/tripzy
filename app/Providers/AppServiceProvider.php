<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer: Logika ini akan dijalankan di semua file tampilan (blade)
        View::composer('*', function ($view) {
            $notifs = collect();

            if (Auth::check()) {
                // Ambil booking user yang sudah dibayar/sedang berjalan
                $bookings = Booking::with('car')
                    ->where('user_id', Auth::id())
                    ->whereIn('status', ['Ongoing', 'Paid']) 
                    ->get();

                $now = Carbon::now();

                foreach ($bookings as $booking) {
                    // Gabungkan tanggal dan jam menjadi satu format waktu
                    $pickupTime = $booking->pickup_time ?? '00:00:00';
                    $pickupDateTime = Carbon::parse($booking->start_date->format('Y-m-d') . ' ' . $pickupTime);
                    
                    // Asumsi batas waktu kembali sama dengan jam pengambilan di hari terakhir
                    $returnDateTime = Carbon::parse($booking->end_date->format('Y-m-d') . ' ' . $pickupTime);

                    // LOGIKA 1: TELAT MENGEMBALIKAN (Lebih dari return time)
                    if ($now->greaterThan($returnDateTime)) {
                        $daysLate = $now->diffInDays($returnDateTime);
                        
                        if ($daysLate > 0) {
                            $notifs->push([
                                'title' => 'TELAT MENGEMBALIKAN',
                                'message' => "Anda telat mengembalikan {$booking->car->name} selama {$daysLate} hari. Segera kembalikan ke garasi.",
                                'time' => $returnDateTime->diffForHumans() // Contoh: "2 days ago"
                            ]);
                        } else {
                            $notifs->push([
                                'title' => 'WAKTU HABIS',
                                'message' => "Durasi rental {$booking->car->name} habis hari ini. Silahkan kembalikan mobil tepat waktu.",
                                'time' => 'Hari ini'
                            ]);
                        }
                    }
                    // LOGIKA 2: WAKTU PENGAMBILAN (Sekarang sudah lewat/sama dengan jam ambil)
                    elseif ($now->greaterThanOrEqualTo($pickupDateTime) && $now->lessThan($returnDateTime)) {
                        $notifs->push([
                            'title' => 'WAKTU PENGAMBILAN',
                            'message' => "Waktu rental sudah dimulai! Silahkan ambil mobil {$booking->car->name} Anda di garasi Tripzy sekarang.",
                            'time' => $pickupDateTime->diffForHumans()
                        ]);
                    }
                }
            }

            // Lempar variabel $customNotifications ke semua blade
            $view->with('customNotifications', $notifs);
        });
    }
}