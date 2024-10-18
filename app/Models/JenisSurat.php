<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisSurat extends Model
{
    use HasFactory;
    protected $table = 'jenis_surats';
    protected $fillable = ['nama'];

    public function surat()
    {
        return $this->hasMany(Surat::class); // Menghubungkan dengan model Surat
    }
}
