<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat extends Model
{
    use HasFactory;
    
    protected $table = 'surat'; // Sesuaikan nama tabel di sini

    protected $fillable = [
        'nomor_per_prodi', 'jenis_surat', 'prodi_id', 'nomor_surat', 'perihal', 'isi'
    ];

    // Definisi relasi dengan model Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat', 'id');
    }

}
