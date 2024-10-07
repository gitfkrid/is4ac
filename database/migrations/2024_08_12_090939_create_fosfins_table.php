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
        Schema::create('fosfin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_alat');
            $table->foreign('id_alat')->references('id_alat')->on('alat')->onDelete('cascade');
            $table->decimal('fosfin', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fosfin');
    }
};
