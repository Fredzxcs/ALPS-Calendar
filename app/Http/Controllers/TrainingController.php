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
use Illuminate\Support\Facades\Mail;
use App\Mail\TrainingNotificationMail;
use App\Mail\TrainingReassignmentMail;


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
            'facilitator_id' => ['nullable', 'integer'], // Facilitator ID can be null
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
            $training = Training::create([
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
                'training_id' => $training->id,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
            ]);

            // Commit the transaction
            \DB::commit();

            // ✅ Check if facilitator_id exists before querying
            if (!empty($request->facilitator_id)) {
                $facilitator = User::find($request->facilitator_id);
                if ($facilitator && !empty($facilitator->email)) {

                    \Log::info('Email Data:', [
                        'training' => $training ? $training->toArray() : 'NULL',
                        'facilitator' => $facilitator ? $facilitator->toArray() : 'NULL',
                    ]);

                    $trainingInfo = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
                                ->find($training->id); // Replace $id with the actual training ID

                    \Log::info('Training Data', [
                        'data' => $trainingInfo ? $trainingInfo->toArray() : 'NULL',
                    ]);

                    Mail::to($facilitator->email)->send(new TrainingNotificationMail($trainingInfo, $facilitator));
                } else {
                    \Log::warning('Facilitator email not found', ['facilitator' => $facilitator]);
                }
            }

            // Return success response
            return response()->json([
                'message' => '200',
                'training' => $training,
                'schedule' => $schedule,
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();

            // ✅ Log full error details, including file and line number
            \Log::error('Training Store Error', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Error occurred during saving the session and schedule.',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'request_data' => $request->all(),
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
        // Validate request
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator_id' => ['nullable', 'integer'],
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

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        \DB::beginTransaction();

        try {
            // Find the training session
            $trainingSession = Training::findOrFail($id);
            $previousFacilitator = User::find($trainingSession->facilitator_id);
            $newFacilitator = User::find($request->facilitator_id);

            // Check if the facilitator is changed
            $facilitatorChanged = $previousFacilitator && $newFacilitator && $previousFacilitator->id !== $newFacilitator->id;

            // Update training session
            $trainingSession->update([
                'course_id' => $request->course_id,
                'mode' => $request->mode,
                'facilitator_id' => $request->facilitator_id,
                'location' => $request->location,
                'platform' => $request->platform,
                'company_id' => $request->company_id,
                'assistant' => $request->assistant,
                'account_id' => $request->account_id,
                'is_updated' => !$facilitatorChanged, // Set to true only if facilitator is unchanged
            ]);

            // Update schedule
            $schedule = Schedule::where('training_id', $trainingSession->id)->first();
            $schedule->update([
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
            ]);

            \DB::commit();

            // === Email Notification Logic ===
            if ($facilitatorChanged) {
                // Facilitator changed
                Mail::to($previousFacilitator->email)
                    ->send(new TrainingReassignmentMail($trainingSession, $previousFacilitator, $newFacilitator));

                Mail::to($newFacilitator->email)
                    ->send(new TrainingNotificationMail($trainingSession, $newFacilitator));
            } elseif ($newFacilitator) {
                // Facilitator unchanged, send update email
                Mail::to($newFacilitator->email)
                    ->send(new TrainingNotificationMail($trainingSession, $newFacilitator));

                // Reset is_updated back to false after sending the email
                $trainingSession->update(['is_updated' => false]);
            } elseif ($previousFacilitator && !$newFacilitator) {
                // Facilitator removed, notify previous facilitator
                Mail::to($previousFacilitator->email)
                    ->send(new TrainingReassignmentMail($trainingSession, $previousFacilitator, null));
            }

            return response()->json([
                'code' => '200',
                'message' => 'Training session updated successfully and notification emails sent',
                'trainingSession' => $trainingSession,
                'schedule' => $schedule,
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Error occurred during updating the session.',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
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
