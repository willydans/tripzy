<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tambahin kolom-kolom baru buat nyimpen data pengembalian mobil
            $table->string('body_condition')->nullable();
            $table->text('body_notes')->nullable();
            
            $table->string('interior_condition')->nullable();
            $table->text('interior_notes')->nullable();
            
            $table->boolean('chk_scratches')->default(false);
            $table->boolean('chk_lights')->default(false);
            $table->boolean('chk_toolkit')->default(false);
            $table->boolean('chk_sparetire')->default(false);
            $table->string('tire_condition')->nullable();
            
            $table->integer('mileage')->nullable();
            $table->integer('delay_hours')->default(0);
            
            $table->integer('damage_fine')->default(0);
            $table->integer('late_fine')->default(0);
            
            $table->text('general_notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Rollback jika diperlukan
            $table->dropColumn([
                'body_condition', 'body_notes', 'interior_condition', 'interior_notes',
                'chk_scratches', 'chk_lights', 'chk_toolkit', 'chk_sparetire', 'tire_condition',
                'mileage', 'delay_hours', 'damage_fine', 'late_fine', 'general_notes'
            ]);
        });
    }
};