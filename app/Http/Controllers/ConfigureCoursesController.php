<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courses; // Ensure the model name matches

class ConfigureCoursesController extends Controller
{
    public function showCourses()
    {
        // Retrieve all courses from the database
        $courses = Courses::all();

        // Pass courses to the view
        return view('configuration.courses', compact('courses'));
    }

    public function storeCourse(Request $request)
    {
        dd($request->all());
        // Validate the incoming data
        $validated = $request->validate([
            'course_name' => 'required|max:255',
            'course_code' => 'nullable|unique:courses|max:255',
        ]);

        // Create and store the new course
        $course = new Courses(); // Use the correct model name (Courses)
        $course->course_name = $validated['course_name'];
        $course->course_code = $validated['course_code'] ?? null; // Handle optional course_code
        $course->save();

        // Respond with a success message
        return response()->json([
            'success' => true,
            'message' => 'Course added successfully!',
        ]);
    }
}
