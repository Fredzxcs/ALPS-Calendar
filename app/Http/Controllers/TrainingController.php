<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Training;
use App\Models\Course;
use App\Models\Company;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('main-content.calendar');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();

        $courses = Course::all();

        $companies = Company::all();

        $accounts = Account::all();

        return view('add_training.add_training', compact('users', 'courses', 'companies', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator_id' => ['nullable' , 'integer'],
            'company_id' => ['nullable', 'integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'assistant' => ['nullable', 'string'],
            'account_id' => ['nullable', 'integer'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date'],
            'from_time' => ['required'],
            'platform' => ['nullable'],
            'to_time' => ['required'],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        \DB::beginTransaction();

        try {
            // Create the Training session record
            $trainingSession = Training::create([
                'course_id' => $request->course_id,
                'mode' => $request->mode,
                'facilitator_id' => $request->facilitator_id,
                'location' => $request->location,
                'platform' => $request->platform,
                'company_id' => $request->company_id,
                'assistant' => $request->assistant,
                'account_id' => $request->account_id,
            ]);

            // Create the Schedule record
            $schedule = Schedule::create([
                'training_id' => $trainingSession->id, // Assuming you have a foreign key in Schedule for Training
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
            ]);

            // Commit the transaction
            \DB::commit();

            // Return success response
            return response()->json([
                'message' => '200',
                'trainingSession' => $trainingSession,
                'schedule' => $schedule,
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'Error occurred during saving the session and schedule.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTraining(Request $request)
    {
        // Include the newly added relationships: course and company
        $trainings = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])->get();

        if ($trainings->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data' => $trainings
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No trainings found'
            ], 404);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, int $id)
    {
        // Fetch the training with the related schedule and facilitator
        $training = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])->find($id);

        // Check if the training exists
        if (!$training) {
            \Log::error('Training not found for ID: ' . $id);
            return redirect()->route('calendar')->with('error', 'Training not found.');
        }

        // Fetch all users to populate the facilitator dropdown
        $facilitators = User::all();

        $courses = Course::all();

        $companies = Company::all();

        $accounts = Account::all();

        // Pass the training object and facilitators to the view
        return view('add_training.edit_training', compact('training', 'facilitators', 'courses', 'companies', 'accounts'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator' => ['nullable' , 'integer'],
            'company_id' => ['nullable', 'integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'assistant' => ['nullable', 'string'],
            'account_id' => ['nullable', 'integer'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date'],
            'from_time' => ['required'],
            'platform' => ['nullable'],
            'to_time' => ['required'],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        \DB::beginTransaction();

        try {
            // Find the existing Training session record
            $trainingSession = Training::findOrFail($id);

            // Update the Training session record
            $trainingSession->update([
                'course_id' => $request->course_id,
                'mode' => $request->mode,
                'facilitator_id' => $request->facilitator_id,
                'location' => $request->location,
                'platform' => $request->platform,
                'company_id' => $request->company_id,
                'assistant' => $request->assistant,
                'account_id' => $request->account_id,
            ]);

            // Find the existing Schedule record associated with the training session
            $schedule = Schedule::where('training_id', $trainingSession->id)->first();

            // Update the Schedule record
            $schedule->update([
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
            ]);

            // Commit the transaction
            \DB::commit();

            // Return success response
            return response()->json([
                'code' => '200',
                'message' => 'Training session and schedule updated successfully',
                'trainingSession' => $trainingSession,
                'schedule' => $schedule,
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();

            // Return error response
            return response()->json([
                'message' => 'Error occurred during updating the session and schedule.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \DB::beginTransaction();

        try {
            $trainingSession = Training::findOrFail($id);

            $schedule = Schedule::where('training_id', $trainingSession->id)->first();

            if ($schedule) {
                $schedule->delete();
            }

            $trainingSession->delete();

            \DB::commit();

            return response()->json([
                'message' => 'Training session and its associated schedule deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error('Error deleting training session: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error occurred while deleting the training session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
