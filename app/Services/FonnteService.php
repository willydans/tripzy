<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public static function sendWA($target, $message)
    {
        // 1. Pastikan nomor HP nggak kosong
        if (!$target) {
            return false;
        }

        // 2. Format nomor HP (Hanya ambil angkanya saja)
        $target = preg_replace('/[^0-9]/', '', $target);

        // 3. Tembak API Fonnte
        try {
            // 🟢 FIX: Tambahkan withoutVerifying() untuk bypass error SSL di Localhost
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONNTE_TOKEN'), 
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                // countryCode sengaja dihilangkan biar Fonnte otomatis baca 08 atau 628
            ]);

            // Opsional: Catat respon Fonnte ke file log Laravel buat proses debugging
            // Log::info('Fonnte Response: ' . $response->body());

            return $response->json();
        } catch (\Exception $e) {
            // Catat error aslinya ke storage/logs/laravel.log
            Log::error('Gagal kirim WA: ' . $e->getMessage());
            return false;
        }
    }
}