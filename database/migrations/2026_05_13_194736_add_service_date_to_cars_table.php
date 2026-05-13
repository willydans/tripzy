<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            // Nambahin kolom service_date bertipe date, boleh kosong (nullable)
            $table->date('service_date')->nullable()->after('stock');
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            // Buat ngehapus kolom kalau kita ngerollback migration
            $table->dropColumn('service_date');
        });
    }
};