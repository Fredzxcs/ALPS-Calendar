<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'course';

    // Define the columns that are mass assignable
    protected $fillable = [
        'course_code', 
        'course_name', 
        'description', 
    ];

    // Disable timestamps if not used in the database
    public $timestamps = false; 
}
