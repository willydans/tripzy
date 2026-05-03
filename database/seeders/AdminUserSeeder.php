<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Tripzy',
            'email' => 'admin@tripzy.com',
            'nomor_hp' => '085276139801',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'password' => Hash::make('admin12345'), // Passwordnya: admin12345
            'role' => 'admin', // 👇 INI YANG BIKIN DIA BISA MASUK ADMIN PANEL
        ]);
    }
}