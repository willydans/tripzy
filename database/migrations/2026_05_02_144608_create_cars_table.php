<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: HONDA HR-V
            $table->string('slug')->unique();
            $table->text('description'); // Deskripsi mobil
            $table->integer('price_per_day'); // Contoh: 600000
            $table->string('image_path'); // Gambar mobil
            
            // Spesifikasi (Sesuai list badges di halaman detail)
            $table->string('year'); // 2023
            $table->string('transmission'); // AUTOMATIC
            $table->integer('seats'); // 5
            $table->string('color'); // GRAY
            $table->string('fuel_type'); // PETROL
            $table->string('engine_capacity')->nullable(); // 1.5L I-VTEC
            $table->integer('luggage_capacity')->nullable(); // 3 Suitcases
            $table->string('license_plate'); // BE 1051 JV
            
            // Features & Facilities (Disimpan sebagai Array JSON)
            $table->json('facilities')->nullable(); // ["HONDA SENSING", "AIRBAGS", "DIGITAL AC"]
            
            // Kategori dan Status
            $table->string('category'); // SUV, MPV, dll
            $table->enum('status', ['Tersedia', 'Disewa', 'Maintenance'])->default('Tersedia'); // Sesuai dropdown form admin
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
};