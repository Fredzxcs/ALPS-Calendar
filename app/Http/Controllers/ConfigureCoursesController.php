<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // Ensure the model name matches
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $validator = Validator::make($request->all(), [
            'course_name' => ['required', 'string', 'max:255', 'unique:course,course_name'],
            'course_code' => ['nullable', 'string', 'max:255', 'unique:course,course_code'],
        ], [
            'course_name.required' => 'Please enter the course name before saving.',
            'course_name.string' => 'The course name should be written as text.',
            'course_name.max' => 'The course name is too long. Please keep it to 255 characters or fewer.',
            'course_name.unique' => 'That course name already exists. Please choose another one.',
            'course_code.string' => 'The course code should be written as text.',
            'course_code.max' => 'The course code is too long. Please keep it to 255 characters or fewer.',
            'course_code.unique' => 'That course code already exists. Please choose another one.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the course details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

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
        $validator = Validator::make($request->all(), [
            'course_name' => ['required', 'string', 'max:255', Rule::unique('course', 'course_name')->ignore($id)],
            'course_code' => ['nullable', 'string', 'max:255', Rule::unique('course', 'course_code')->ignore($id)],
        ], [
            'course_name.required' => 'Please enter the course name before updating.',
            'course_name.string' => 'The course name should be written as text.',
            'course_name.max' => 'The course name is too long. Please keep it to 255 characters or fewer.',
            'course_name.unique' => 'That course name already exists. Please choose another one.',
            'course_code.string' => 'The course code should be written as text.',
            'course_code.max' => 'The course code is too long. Please keep it to 255 characters or fewer.',
            'course_code.unique' => 'That course code already exists. Please choose another one.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the course details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

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
