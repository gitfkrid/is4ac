<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lokasi')->insert(array(
            [
                'nama_lokasi' => 'FGW B'
            ],
            [
                'nama_lokasi' => 'FGW C'
            ],
            [
                'nama_lokasi' => 'Gudang Jombang'
            ],
            [
                'nama_lokasi' => 'Gudang Lombok'
            ]
        ));
    }
}
