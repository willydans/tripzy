<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // ODR-1234 / ODR-202652
            
            // Relasi ke User & Car
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');
            
            // Detail Pesanan
            $table->date('start_date'); // Date Pickup
            $table->integer('duration'); // Berapa hari
            $table->date('end_date'); // Return Date
            $table->time('pickup_time');
            
            // Data Penyewa Form (Bisa beda dari data user login)
            $table->string('renter_name');
            $table->string('renter_phone');
            $table->string('renter_id_number'); // NIK / KTP
            
            // Renter Documents (Menyimpan path file gambar)
            $table->string('doc_ktp');
            $table->string('doc_sim');
            $table->string('doc_passport')->nullable(); // Boleh kosong
            $table->string('doc_selfie');
            
            // Pembayaran & Opsi
            $table->boolean('with_driver')->default(false); // Checkbox +200.000/day
            $table->string('payment_method')->default('QRIS');
            $table->integer('total_price');
            $table->enum('payment_status', ['Unpaid', 'Paid'])->default('Unpaid'); // Sesuai Detail Booking Admin
            
            // Status Booking (Menggabungkan User UI dan Admin UI)
            $table->enum('status', [
                'Pending Payment', // Menunggu bayar
                'Ordered',         // Sudah bayar, nunggu di-verify admin / nunggu diambil
                'Ongoing',         // Mobil sedang dipakai (Active)
                'History',         // Mobil sudah dikembalikan (Completed)
                'Cancelled'        // Dibatalkan
            ])->default('Pending Payment');
            
            // Waktu Verifikasi Admin (Pas admin klik "Verification" / Confirmation of Pickup)
            $table->timestamp('verified_at')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};