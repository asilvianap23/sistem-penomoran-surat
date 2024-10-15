<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->integer('nomor_per_prodi')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn('nomor_per_prodi');
        });
    }
    
};
