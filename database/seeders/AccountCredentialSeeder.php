<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Account;

class AccountCredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('credentials')->insert([
            [
                'account_email' => 'Training01@gmail.com',
                'account_password' => 'password123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_email' => 'Training02@gmail.com',
                'account_password' => 'password123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_email' => 'Training03@gmail.com',
                'account_password' => 'password123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
