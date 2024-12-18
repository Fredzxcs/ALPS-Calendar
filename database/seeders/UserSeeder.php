<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Inserting existing data manually
        User::create([
            'name' => 'Alps Admin',
            'email' => 'administrator0@gmail.com',
            'usertype' => 'admin',
            'password' => bcrypt('administrator'), // Make sure to hash passwords
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'coordinator0@gmail.com',
            'usertype' => 'coordinator',
            'password' => bcrypt('coordinator'),
        ]);

        User::create([
            'name' => 'Mac Donald',
            'email' => 'facilitator0@gmail.com',
            'usertype' => 'facilitator',
            'password' => bcrypt('facilitator'),
        ]);
        // Add more users as necessary
    }
}
