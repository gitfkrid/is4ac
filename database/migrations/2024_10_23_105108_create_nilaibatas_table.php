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
        Schema::create('nilaibatas', function (Blueprint $table) {
            $table->id('id_nilaibatas');
            $table->decimal('nb_suhu_atas', 8, 2);
            $table->decimal('nb_suhu_bawah', 8, 2);
            $table->decimal('nb_rh_atas', 8, 2);
            $table->decimal('nb_rh_bawah', 8, 2);
            $table->decimal('nb_ph3_atas', 8, 2);
            $table->decimal('nb_ph3_bawah', 8, 2);
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilaibatas');
    }
};
