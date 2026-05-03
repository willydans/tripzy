<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\AdminCarController; 
use App\Http\Controllers\AdminBookingController; 
use App\Http\Controllers\OrderController; // 👈 Wajib ditambahin buat narik data pesanan user
use App\Models\Car; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// ROUTES UNTUK GUEST (BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
    
    // Landing Pages
    Route::get('/', function () { return view('home'); })->name('home');
    Route::get('/catalog', function () { return view('catalog'); })->name('catalog');
    Route::get('/destination', function () { return view('destination'); })->name('destination');
    Route::get('/contact', function () { return view('contact'); })->name('contact');

    // Auth Pages (Login & Register)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);

});


// ==========================================
// ROUTES UNTUK USER (SUDAH LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Dashboard / User Home
    Route::get('/dashboard', function () { 
        return view('user_home'); 
    })->name('dashboard');

    // Catalog Khusus User (Narik Data Dinamis dari Database)
    Route::get('/dashboard/catalog', function () { 
        // Ambil semua mobil yang statusnya 'Tersedia'
        $cars = Car::where('status', 'Tersedia')->get(); 
        
        // Lempar variabel $cars ke tampilan user_catalog
        return view('user_catalog', compact('cars')); 
    })->name('user.catalog');

    // Detail Mobil Khusus (Checkout)
    Route::get('/dashboard/catalog/{slug}', [App\Http\Controllers\CarController::class, 'show'])->name('user.car.detail');

    // 👇 ROUTE UNTUK HALAMAN ORDERS (MY BOOKINGS) 👇
    Route::get('/orders', [OrderController::class, 'index'])->name('user.orders');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel');
    
    // Proses Logout (Bisa dipakai barengan sama admin)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});


// ==========================================
// ROUTES UNTUK ADMIN (SUDAH LOGIN & ROLE = ADMIN)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // ROUTE UNTUK MANAGE CATALOG (CRUD MOBIL)
    Route::get('/admin/catalog', [AdminCarController::class, 'index'])->name('admin.catalog.index');
    Route::post('/admin/catalog', [AdminCarController::class, 'store'])->name('admin.catalog.store');
    Route::put('/admin/catalog/{id}', [AdminCarController::class, 'update'])->name('admin.catalog.update');
    Route::delete('/admin/catalog/{id}', [AdminCarController::class, 'destroy'])->name('admin.catalog.destroy');

    // ROUTE UNTUK BOOKING VERIFICATION
    Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::post('/admin/bookings/{id}/verify', [AdminBookingController::class, 'verify'])->name('admin.bookings.verify');
    Route::post('/admin/bookings/{id}/pickup', [AdminBookingController::class, 'pickup'])->name('admin.bookings.pickup');
    Route::post('/admin/bookings/{id}/return', [AdminBookingController::class, 'returnCar'])->name('admin.bookings.return');
    Route::post('/admin/bookings/{id}/cancel', [AdminBookingController::class, 'cancel'])->name('admin.bookings.cancel');
});