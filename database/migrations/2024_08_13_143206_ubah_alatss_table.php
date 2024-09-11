<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lokasi')->after('id_jenis_alat');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi');
            $table->string('keterangan', 50)->after('nama_device');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
