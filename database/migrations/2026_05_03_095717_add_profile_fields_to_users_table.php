<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kota')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nomor_sim')->nullable();
            $table->string('jenis_sim')->nullable();
            $table->date('masa_berlaku_sim')->nullable();
            $table->string('profile_photo')->nullable(); // Opsional buat foto profil nanti
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kota', 'alamat', 'nomor_sim', 'jenis_sim', 'masa_berlaku_sim', 'profile_photo']);
        });
    }
};