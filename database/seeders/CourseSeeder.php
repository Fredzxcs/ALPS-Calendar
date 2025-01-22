<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('course')->insert([
            [
                'course_name' => 'Agile Scrum Training',
                'course_code' => 'AT01',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_name' => 'Scrum Master Training',
                'course_code' => 'AT02',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_name' => 'Project Management Training',
                'course_code' => 'AT03',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
