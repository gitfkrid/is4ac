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
        Schema::table('log_relay', function($table) {
            $table->decimal('suhu', 8, 2)->after('waktu');
            $table->decimal('kelembaban', 8, 2)->after('suhu');
            $table->boolean('mode')->default(0)->after('kelembaban');
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
