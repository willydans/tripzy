<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarController extends Controller
{
    public function show($slug)
    {
        $car = Car::where('slug', $slug)->firstOrFail();
        
        if ($car->stock <= 0) {
            return redirect()->back()->with('error', 'Mohon maaf, stok unit mobil ' . $car->name . ' sedang kosong atau disewa semua.');
        }

        return view('car_detail', compact('car'));
    }

    // ----------------------------------------------------
    // FUNGSI AI TRIP ASSISTANT (GEMINI API)
    // ----------------------------------------------------
    public function aiRecommend(Request $request)
    {
        $userQuery = $request->input('query');

        $cars = Car::where('stock', '>', 0)
                   ->get(['id', 'name', 'category', 'seats', 'transmission', 'description', 'price_per_day']);
        
        $carsJson = json_encode($cars);

        $prompt = "Kamu adalah asisten rental mobil bernama Tripzy AI. Daftar mobil JSON: " . $carsJson . ". 
                   Pelanggan request: '" . $userQuery . "'. 
                   Pilih mobil yang paling cocok. Balas HANYA dengan array ID mobil, contoh: [1, 2]. Jangan tulis penjelasan apapun.";

        try {
           
// Ganti baris URL-nya menjadi ini:
$response = Http::withoutVerifying()->withHeaders([
    'Content-Type' => 'application/json',
])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . config('services.gemini.key'), [
    'contents' => [
        ['parts' => [['text' => $prompt]]]
    ]
]);

            $result = $response->json();
            
            // 🟢 TANGKAP ERROR DARI GOOGLE GEMINI 🟢
            if (isset($result['error'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesan dari Google: ' . $result['error']['message']
                ]);
            }
            
            // Ekstrak jawaban Gemini
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $geminiText = $result['candidates'][0]['content']['parts'][0]['text'];
                
                preg_match('/\[[\d\s,]+\]/', $geminiText, $matches);
                
                if (!empty($matches)) {
                    $recommendedIds = json_decode($matches[0], true);
                } else {
                    preg_match_all('/\d+/', $geminiText, $numberMatches);
                    $recommendedIds = !empty($numberMatches[0]) ? array_map('intval', $numberMatches[0]) : [];
                }

                if (is_array($recommendedIds) && count($recommendedIds) > 0) {
                    $recommendedCars = Car::whereIn('id', $recommendedIds)->get();
                    
                    if ($recommendedCars->count() > 0) {
                        return response()->json([
                            'success' => true,
                            'data' => $recommendedCars
                        ]);
                    }
                }

                return response()->json([
                    'success' => false, 
                    'message' => 'AI menjawab format yg aneh: ' . $geminiText
                ]);
            }

            return response()->json(['success' => false, 'message' => 'AI kebingungan membaca data mobil.']);

       } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'DEBUG ERROR: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}