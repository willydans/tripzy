<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController; // 👈 Import controller Google Socialite
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\AdminCarController; 
use App\Http\Controllers\AdminBookingController; 
use App\Http\Controllers\AdminTransactionController; 
use App\Http\Controllers\AdminUserController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\PaymentCallbackController;
use App\Models\Car; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// ROUTE WEBHOOK MIDTRANS (TANPA MIDDLEWARE)
// ==========================================
// Route ini diletakkan di luar middleware auth agar dapat diakses oleh server Midtrans
Route::post('/midtrans/callback', [PaymentCallbackController::class, 'receive']);


// ==========================================
// ROUTES UNTUK GUEST (BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
    
    // Landing Pages
    Route::get('/', function () { 
        // Tarik maksimal 4 mobil yang statusnya 'Tersedia' buat dipajang di Home
        $cars = Car::where('status', 'Tersedia')->take(4)->get();
        return view('home', compact('cars')); 
    })->name('home');

    Route::get('/catalog', function () { 
        // Tarik semua mobil buat halaman Catalog (Guest)
        $cars = Car::where('status', 'Tersedia')->get();
        return view('catalog', compact('cars')); 
    })->name('catalog');
    
    Route::get('/destination', function () { return view('destination'); })->name('destination');
    Route::get('/contact', function () { return view('contact'); })->name('contact');

    // Auth Pages (Login & Register Manual)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);

    // 🟢 ROUTES OAUTH GOOGLE LOGIN/REGISTER 🟢
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

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
        $cars = Car::where('status', 'Tersedia')->get(); 
        return view('user_catalog', compact('cars')); 
    })->name('user.catalog');

    // Detail Mobil Khusus (Checkout)
    Route::get('/dashboard/catalog/{slug}', [App\Http\Controllers\CarController::class, 'show'])->name('user.car.detail');
    Route::post('/dashboard/catalog/{slug}/checkout', [OrderController::class, 'store'])->name('user.checkout.process');
    
    // ROUTE UNTUK HALAMAN PAYMENT (QRIS)
    Route::get('/dashboard/payment/{id}', [OrderController::class, 'payment'])->name('user.payment');

    // ROUTE UNTUK HALAMAN DESTINATION USER
    Route::get('/dashboard/destination', function () {
        return view('user_destination');
    })->name('user.destination');

    // ROUTE UNTUK HALAMAN ORDERS (MY BOOKINGS)
    Route::get('/orders', [OrderController::class, 'index'])->name('user.orders');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel');
    
    // INI RUTENYA BUAT PROSES TOMBOL "PAYMENT NOW" 
    Route::post('/orders/{id}/pay', [OrderController::class, 'pay'])->name('user.orders.pay'); 
    
    // ROUTE UNTUK USER PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('user.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    
    // Proses Logout
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

    // ROUTE UNTUK TRANSACTIONS ADMIN 
    Route::get('/admin/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');
    Route::get('/admin/transactions/export', [AdminTransactionController::class, 'export'])->name('admin.transactions.export');

    // ROUTE BARU BUAT USER MANAGEMENT
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{id}/verify', [AdminUserController::class, 'verify'])->name('admin.users.verify');
    Route::post('/admin/users/{id}/blacklist', [AdminUserController::class, 'blacklist'])->name('admin.users.blacklist');
});