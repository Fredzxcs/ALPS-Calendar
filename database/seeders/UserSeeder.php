<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'username' => 'alpsadmin',
                'email' => 'johndoe@example.com',
                'usertype' => 'admin',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'alpscoordinator',
                'email' => 'janesmith@example.com',
                'usertype' => 'coordinator',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mary Grace',
                'username' => 'alpsfacilitator',
                'email' => 'marygrace@example.com',
                'usertype' => 'facilitator',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}