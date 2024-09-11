<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_lokasi')->insert(array(
            [
                'id_user' => '1',
                'id_lokasi' => '1',
            ],
            [
                'id_user' => '1',
                'id_lokasi' => '2',
            ],
            [
                'id_user' => '1',
                'id_lokasi' => '3',
            ],
            [
                'id_user' => '1',
                'id_lokasi' => '4',
            ],
        ));
    }
}
