<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Pastikan untuk mengimpor model User

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com', // Ganti dengan email admin yang diinginkan
            'password' => Hash::make('password'), // Ganti dengan password yang diinginkan
            'role' => 'admin', // Pastikan kolom ini ada di tabel users
        ]);
    }
}
