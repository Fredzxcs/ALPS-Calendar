<?php

namespace Database\Factories;

use App\Models\Course; // Import the Course model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courses>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'course_code' => strtoupper($this->faker->lexify('CS-???')),
            'course_name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(),
        ];
    }
}
