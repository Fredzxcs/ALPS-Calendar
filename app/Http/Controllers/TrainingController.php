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
use App\Mail\CancelledTrainingMail;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Auth;


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
            'course_id' => ['required', 'integer', 'exists:course,id'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator_id' => ['nullable', 'integer', 'exists:users,id'], // Facilitator ID can be null
            'company_id' => ['nullable', 'integer', 'exists:company,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'assistant' => ['nullable', 'string'],
            'account_id' => ['nullable', 'integer', 'exists:credentials,id'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date'],
            'from_time' => ['required'],
            'platform' => ['nullable'],
            'conference_link' => ['nullable', 'url'],
            'need_transportation' => ['nullable'],
            'outbound_pickup_time' => ['nullable'],
            'outbound_contact_number' => ['nullable', 'string', 'max:255'],
            'outbound_pickup_location' => ['nullable', 'string', 'max:255'],
            'outbound_dropoff_location' => ['nullable', 'string', 'max:255'],
            'return_trip_needed' => ['nullable'],
            'return_pickup_time' => ['nullable'],
            'return_contact_number' => ['nullable', 'string', 'max:255'],
            'return_pickup_location' => ['nullable', 'string', 'max:255'],
            'return_dropoff_location' => ['nullable', 'string', 'max:255'],
            'notify_coordinator' => ['nullable'],
            'coordinator_to_notify' => ['nullable', 'integer', 'exists:users,id'],
            'to_time' => ['required'],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Additional explicit existence checks to avoid DB FK exceptions
        $missing = [];
        if (!Course::find($request->course_id)) {
            $missing['course_id'] = ['Selected course does not exist in the database.'];
        }
        if ($request->filled('company_id') && !Company::find($request->company_id)) {
            $missing['company_id'] = ['Selected company does not exist in the database.'];
        }
        if ($request->filled('facilitator_id') && !User::find($request->facilitator_id)) {
            $missing['facilitator_id'] = ['Selected facilitator does not exist in the database.'];
        }
        if ($request->filled('account_id') && !Account::find($request->account_id)) {
            $missing['account_id'] = ['Selected account/account credentials do not exist.'];
        }
        if ($request->filled('coordinator_to_notify') && !User::find($request->coordinator_to_notify)) {
            $missing['coordinator_to_notify'] = ['Selected coordinator does not exist in the database.'];
        }

        if (!empty($missing)) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $missing,
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
                'conference_link' => $request->conference_link,
                'company_id' => $request->company_id,
                'assistant' => $request->assistant,
                'account_id' => $request->account_id,
                'need_transportation' => $request->boolean('need_transportation'),
                'outbound_pickup_time' => $request->outbound_pickup_time,
                'outbound_contact_number' => $request->outbound_contact_number,
                'outbound_pickup_location' => $request->outbound_pickup_location,
                'outbound_dropoff_location' => $request->outbound_dropoff_location,
                'return_trip_needed' => $request->boolean('return_trip_needed'),
                'return_pickup_time' => $request->return_pickup_time,
                'return_contact_number' => $request->return_contact_number,
                'return_pickup_location' => $request->return_pickup_location,
                'return_dropoff_location' => $request->return_dropoff_location,
                'notify_coordinator' => $request->boolean('notify_coordinator'),
                'coordinator_to_notify' => $request->coordinator_to_notify,
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

            // Create Google Calendar event if the current user has connected Google
            try {
                $currentUser = Auth::user();
                $isDemo = filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN);
                $googleRefreshToken = null;
                
                // Check if user has Google connection (either from model or session in demo mode)
                $hasGoogleConnection = false;
                if ($currentUser && isset($currentUser->google_refresh_token) && !empty($currentUser->google_refresh_token)) {
                    $hasGoogleConnection = true;
                } else if ($isDemo && session('google_connected') && session('google_refresh_token')) {
                    $hasGoogleConnection = true;
                    $googleRefreshToken = session('google_refresh_token');
                }
                
                if ($hasGoogleConnection) {
                    $googleService = new GoogleCalendarService();

                    // Build attendees list: include facilitator if available and has email
                    $attendees = [];
                    if (!empty($request->facilitator_id)) {
                        $fac = User::find($request->facilitator_id);
                        if ($fac && !empty($fac->email)) {
                            $attendees[] = $fac->email;
                        }
                    }

                    // Include assistants (comma-separated IDs)
                    if (!empty($request->assistant)) {
                        $assistantIds = array_filter(array_map('trim', explode(',', $request->assistant)));
                        foreach ($assistantIds as $aid) {
                            if (is_numeric($aid)) {
                                $aUser = User::find((int) $aid);
                                if ($aUser && !empty($aUser->email)) {
                                    $attendees[] = $aUser->email;
                                }
                            }
                        }
                    }

                    // Insert event on connected user's primary calendar and send invites
                    $trainingInfo = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
                                ->find($training->id);

                    // Use schedule created earlier (pass refresh token for demo mode)
                    $googleService->createEvent($currentUser, $trainingInfo, $schedule, $attendees, $googleRefreshToken);
                }
            } catch (\Exception $e) {
                \Log::error('Google Calendar sync failed: ' . $e->getMessage());
            }

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
                
                    // Notify assistants by email as well
                    if (!empty($request->assistant)) {
                        $assistantIds = array_filter(array_map('trim', explode(',', $request->assistant)));
                        foreach ($assistantIds as $aid) {
                            if (is_numeric($aid)) {
                                $aUser = User::find((int) $aid);
                                if ($aUser && !empty($aUser->email)) {
                                    Mail::to($aUser->email)->send(new TrainingNotificationMail($trainingInfo, $aUser));
                                }
                            }
                        }
                    }
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
            // If this is a DB QueryException, surface a clearer message for FK violations
            if ($e instanceof \Illuminate\Database\QueryException) {
                $sqlState = $e->errorInfo[0] ?? null;
                $driverCode = $e->errorInfo[1] ?? null;
                $message = $e->getMessage();
                if (strpos($message, 'FOREIGN KEY constraint failed') !== false || $sqlState === '23000') {
                    return response()->json([
                        'message' => 'Training creation failed due to invalid foreign key reference.',
                        'error' => $message,
                        'request_data' => $request->all(),
                    ], 422);
                }
                return response()->json([
                    'message' => 'Database query error during training creation.',
                    'error' => $message,
                    'request_data' => $request->all(),
                ], 500);
            }

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
            'conference_link' => ['nullable', 'url'],
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
                'conference_link' => $request->conference_link,
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

            if($trainingSession->facilitator_id)
            {
                $facilitator = User::findOrFail($trainingSession->facilitator_id);

                Mail::to($facilitator->email)
                    ->send(new CancelledTrainingMail($trainingSession, $facilitator));
            }

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


