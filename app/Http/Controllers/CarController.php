<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function show($slug)
    {
        // Cari data mobil berdasarkan slug di database
        $car = Car::where('slug', $slug)->firstOrFail();
        
        return view('car_detail', compact('car'));
    }
}