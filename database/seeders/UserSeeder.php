<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Ops',
                'email' => 'admin@opssight.com',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operator 1',
                'email' => 'operator1@opssight.com',
                'password' => Hash::make('password'),
                'role' => 'OPERATOR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operator 2',
                'email' => 'operator2@opssight.com',
                'password' => Hash::make('password'),
                'role' => 'OPERATOR',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
