<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisAlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_alat')->insert(array(
            [
                'jenis_alat' => 'PH3'
            ],
            [
                'jenis_alat' => 'DHT'
            ]
        ));
    }
}
