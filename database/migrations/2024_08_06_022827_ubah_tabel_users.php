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
        Schema::table('users', function (Blueprint $table) {
            // $table->uuid('uuid')->after('id')->nullable();
            // $table->string('hp', 14)->after('email')->nullable();
            // $table->text('alamat')->after('hp');
            $table->unsignedBigInteger('id_level')->after('password');
            $table->foreign('id_level')->references('id_level')->on('level');
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
