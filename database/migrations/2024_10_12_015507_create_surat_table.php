<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('jenis_surat'); // Jenis surat (misalnya, surat resmi, undangan, dll)
            $table->string('prodi_id'); // Program studi
            $table->string('nomor_surat')->unique(); // Nomor surat
            $table->string('perihal'); // Perihal surat
            $table->text('isi'); // Isi surat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
