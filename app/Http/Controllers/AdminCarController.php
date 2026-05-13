<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCarController extends Controller
{
    // 1. Tampilkan Halaman Manage Catalog
    public function index()
    {
        $cars = Car::orderBy('created_at', 'desc')->get();
        return view('admin.manage_catalog', compact('cars'));
    }

    // 2. Simpan Mobil Baru (Create)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255',
            'category' => 'required|string',
            'status' => 'required|string',
            'price_per_day' => 'required|integer',
            'stock' => 'required|integer|min:0', // <-- Validasi Stock
            'service_date' => 'nullable|date', // <-- Validasi Tanggal Servis
            'year' => 'required|string',
            'color' => 'required|string',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Upload Gambar
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images/cars'), $imageName);
        $imagePath = 'images/cars/' . $imageName;

        Car::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . Str::random(5)),
            'description' => 'Kenyamanan berkendara dengan ' . $request->name, // Default text
            'price_per_day' => $request->price_per_day,
            'stock' => $request->stock, // <-- Simpan Stock
            'service_date' => $request->service_date, // <-- Simpan Tanggal Servis
            'image_path' => $imagePath,
            'year' => $request->year,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'color' => $request->color,
            'fuel_type' => $request->fuel_type,
            'license_plate' => $request->license_plate,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', $request->name . ' car successfully added');
    }

    // 3. Update Mobil (Edit)
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255',
            'price_per_day' => 'required|integer',
            'stock' => 'required|integer|min:0', // <-- Validasi Stock
            'service_date' => 'nullable|date', // <-- Validasi Tanggal Servis
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if (file_exists(public_path($car->image_path))) {
                @unlink(public_path($car->image_path));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/cars'), $imageName);
            $car->image_path = 'images/cars/' . $imageName;
        }

        // Update data
        $car->name = $request->name;
        $car->license_plate = $request->license_plate;
        $car->category = $request->category;
        $car->status = $request->status;
        $car->price_per_day = $request->price_per_day;
        $car->stock = $request->stock; // <-- Update Stock
        $car->service_date = $request->service_date; // <-- Update Tanggal Servis
        $car->year = $request->year;
        $car->color = $request->color;
        $car->fuel_type = $request->fuel_type;
        $car->transmission = $request->transmission;
        $car->seats = $request->seats;
        $car->save();

        return redirect()->back()->with('success', $request->name . ' car successfully edited');
    }

    // 4. Hapus Mobil (Delete)
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $carName = $car->name;
        
        // Hapus file gambar dari folder
        if (file_exists(public_path($car->image_path))) {
            @unlink(public_path($car->image_path));
        }

        $car->delete();

        return redirect()->back()->with('success', $carName . ' car successfully deleted');
    }
}