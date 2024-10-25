<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiBatasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nilaibatas')->insert([
            'nb_suhu_atas' => 35.00,
            'nb_suhu_bawah' => 15.00,
            'nb_rh_atas' => 75.00,
            'nb_rh_bawah' => 35.00,
            'nb_ph3_atas' => 10.00,
            'nb_ph3_bawah' => 0.00,
        ]);
    }
}
