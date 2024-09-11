<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(array(
            [
                'name' => 'Admin MDR',
                'email' => 'ptmdr@gmail.com',
                'email_verified_at' => now(),
                'id_level' => '1',
                'password' => bcrypt('LFSmJrhqTnZtV*h'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff MDR',
                'email' => 'staffmdr@gmail.com',
                'email_verified_at' => now(),
                'id_level' => '2',
                'password' => bcrypt('ugQ5TC.JAwyEx'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thoriq Lukman Hakim',
                'email' => 'thor@gmail.com',
                'email_verified_at' => now(),
                'id_level' => '2',
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));
    }
}
