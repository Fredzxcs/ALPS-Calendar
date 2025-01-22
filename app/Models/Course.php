<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Training;


class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CoursesFactory> */
    use HasFactory;

    protected $table = 'course';

    // Define the columns that are mass assignable
    protected $fillable = [
        'course_code',
        'course_name',
        'description',
    ];

    // Disable timestamps if not used in the database
    public $timestamps = false;

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

}
