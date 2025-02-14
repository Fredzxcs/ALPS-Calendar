<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // Ensure the model name matches

class ConfigureCoursesController extends Controller
{
    public function showCourse()
    {
        // Retrieve all courses from the database
        $courses = Course::orderBy('course_name', 'asc')->get();

        // Pass courses to the view
        return view('configuration.courses', compact('courses'));
    }

    public function showCourseDetails($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json([
            'course_name' => $course->course_name,
            'course_code' => $course->course_code
        ]);
    }

    public function addCourse(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'course_name' => 'required|unique:course|max:255',
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

    public function editCourse(Request $request, $id)
    {
        // Validate the incoming data
        $rules = [
            'course_name' => 'required|max:255',
            'course_code' => "nullable|unique:course,course_code,{$id}|max:255",
        ];


        $validated = $request->validate($rules);

        // Find the company by ID
        $course = Course::findOrFail($id);

        // Update the company's details
        $course->fill($validated);

        $course->save();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully!',
        ]);
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully!',
        ]);
    }
}
