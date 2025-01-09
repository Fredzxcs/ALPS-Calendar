<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Training;
use Illuminate\Support\Facades\Validator;

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

        return view('add_training.add_training', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'course' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator_id' => ['nullable' , 'integer'],
            'company' => ['nullable', 'string', 'max:255'],
            'assistant_id' => ['nullable', 'string'],
            'credentials_email' => ['nullable'],
            'credentials_password' => ['nullable', 'string'],
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
                'course' => $request->course,
                'mode' => $request->mode,
                'facilitator_id' => $request->facilitator_id,
                'company' => $request->company,
                'assistant_id' => $request->assistant_id,
                'credentials_email' => $request->credentials_email,
                'credentials_password' => $request->credentials_password, // Encrypt the password
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

    public function gettraining(Request $request)
    {

        $trainings = Training::with(['schedule','facilitator'])->get();

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
