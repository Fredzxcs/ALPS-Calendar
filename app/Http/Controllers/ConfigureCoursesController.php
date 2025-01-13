<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // Ensure the model name matches

class ConfigureCoursesController extends Controller
{
    public function showCourses()
    {
        // Retrieve all courses from the database
        $courses = Course::all();

        // Pass courses to the view
        return view('configuration.courses', compact('courses'));
    }

    public function addCourse(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'course_name' => 'required|max:255',
            'course_code' => 'nullable|unique:course|max:255', // Use correct table name
        ]);
    
        // Create and store the new course
        $course = new Course();
        $course->course_name = $validated['course_name'];
        $course->course_code = $validated['course_code'] ?? null;
        $course->save();
    
        // Respond with a success message
        return response()->json([
            'success' => true,
            'message' => 'Course added successfully!',
        ]);
    }
    
}
