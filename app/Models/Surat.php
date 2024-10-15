<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat extends Model
{
    use HasFactory;
    
    protected $table = 'surat'; // Sesuaikan nama tabel di sini

    protected $fillable = [
        'nomor_per_prodi', 'jenis_surat', 'prodi', 'nomor_surat', 'perihal', 'isi'
    ];

    // Definisi relasi dengan model Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi');
    }

}
