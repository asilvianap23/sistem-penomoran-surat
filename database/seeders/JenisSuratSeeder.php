<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisSurat::create(['nama' => 'Surat Bebas Pustaka']);
        JenisSurat::create(['nama' => 'Surat Bukti Upload Metopen']);
        JenisSurat::create(['nama' => 'Surat Bukti Upload KP']);
    }
}
