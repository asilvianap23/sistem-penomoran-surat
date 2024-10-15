<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prodi extends Model
{
    use HasFactory; // Menggunakan trait HasFactory

    protected $table = 'prodi';
    protected $fillable = ['nama', 'deskripsi'];

    public function surat()
    {
        return $this->hasMany(Surat::class); // Menghubungkan dengan model Surat
    }
}
