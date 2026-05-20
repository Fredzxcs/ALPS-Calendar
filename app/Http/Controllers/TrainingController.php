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
use App\Mail\DriverNotificationMail;
use App\Mail\TrainingReassignmentMail;
use App\Mail\CancelledTrainingMail;
use App\Mail\DriverCancellationMail;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Auth;


class TrainingController extends Controller
{
    private function shouldSendMailTo(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        // Avoid sending in local/demo-style placeholder accounts.
        $normalized = strtolower(trim($email));
        if (str_contains($normalized, '@example.com') || str_contains($normalized, '@alps.local')) {
            return false;
        }

        return true;
    }

    private function collectUserEmailsFromIds(?string $idList): array
    {
        $emails = [];

        foreach ($this->extractCoordinatorIds($idList) as $userId) {
            if (!is_numeric($userId)) {
                continue;
            }

            $user = User::find((int) $userId);
            if ($user && $this->shouldSendMailTo($user->email)) {
                $emails[] = $user->email;
            }
        }

        return array_values(array_unique($emails));
    }

    private function sendDriverCancellationNotifications(Training $trainingSession): void
    {
        $recipientIds = $this->extractCoordinatorIds(
            $trainingSession->coordinator_to_notify_list ?? $trainingSession->coordinator_to_notify ?? ''
        );

        if (empty($recipientIds)) {
            return;
        }

        $trainingInfo = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
            ->find($trainingSession->id) ?? $trainingSession;

        foreach ($recipientIds as $recipientId) {
            $recipient = User::find((int) $recipientId);

            if (!$recipient || empty($recipient->email) || !$this->shouldSendMailTo($recipient->email)) {
                Log::warning('Skipping driver cancellation notification due to missing/invalid email', [
                    'coordinator_id' => $recipientId,
                ]);
                continue;
            }

            try {
                Mail::to($recipient->email)->send(new DriverCancellationMail($trainingInfo, $recipient));
            } catch (\Exception $e) {
                Log::error('Failed to send driver cancellation notification', [
                    'to' => $recipient->email,
                    'coordinator_id' => $recipient->id ?? null,
                    'exception' => $e->getMessage(),
                ]);
            }
        }
    }

    private function resolveAssistantNames(?string $assistantValue): string
    {
        if (trim((string) $assistantValue) === '') {
            return '';
        }

        $assistantIds = array_filter(array_map('trim', explode(',', (string) $assistantValue)));
        $names = [];

        foreach ($assistantIds as $assistantId) {
            if (!is_numeric($assistantId)) {
                continue;
            }

            $user = User::find((int) $assistantId);
            if ($user && !empty($user->name)) {
                $names[] = $user->name;
            }
        }

        return implode(', ', $names);
    }

    private function extractCoordinatorIds(?string $coordinatorValue): array
    {
        if (trim((string) $coordinatorValue) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $coordinatorValue))));
    }

    private function resolveCoordinatorNames(?string $coordinatorValue): string
    {
        if (trim((string) $coordinatorValue) === '') {
            return '';
        }

        $coordinatorIds = $this->extractCoordinatorIds($coordinatorValue);
        $names = [];

        foreach ($coordinatorIds as $coordinatorId) {
            if (!is_numeric($coordinatorId)) {
                continue;
            }

            $user = User::find((int) $coordinatorId);
            if ($user && !empty($user->name)) {
                $names[] = $user->name;
            }
        }

        return implode(', ', $names);
    }

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
        // Convert empty facilitator_id and account_manager_id to null
        if ($request->filled('facilitator_id') === false || $request->input('facilitator_id') === '') {
            $request->merge(['facilitator_id' => null]);
        }
        if ($request->filled('account_manager_id') === false || $request->input('account_manager_id') === '') {
            $request->merge(['account_manager_id' => null]);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer', 'exists:course,id'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator_id' => ['nullable', 'integer', 'exists:users,id'], // Facilitator ID can be null
            'account_manager_id' => ['nullable', 'integer', 'exists:users,id'], // Account Manager ID can be null
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
            'coordinator_to_notify_list' => ['required_if:notify_coordinator,1', 'nullable', 'string'],
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
        if ($request->filled('account_manager_id') && !User::find($request->account_manager_id)) {
            $missing['account_manager_id'] = ['Selected account manager does not exist in the database.'];
        }
        if ($request->filled('account_id') && !Account::find($request->account_id)) {
            $missing['account_id'] = ['Selected account/account credentials do not exist.'];
        }
        $coordinatorIds = $this->extractCoordinatorIds($request->input('coordinator_to_notify_list', $request->input('coordinator_to_notify', '')));
        foreach ($coordinatorIds as $coordinatorId) {
            if (is_numeric($coordinatorId) && !User::find((int) $coordinatorId)) {
                $missing['coordinator_to_notify_list'] = ['Selected coordinator does not exist in the database.'];
                break;
            }
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
                'account_manager_id' => $request->account_manager_id,
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
                'coordinator_to_notify_list' => $request->coordinator_to_notify_list,
                'coordinator_to_notify' => $coordinatorIds[0] ?? null,
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
                $googleAccessToken = null;
                
                // Check if user has Google connection (either from model or session in demo mode)
                $hasGoogleConnection = false;
                if ($currentUser && isset($currentUser->google_refresh_token) && !empty($currentUser->google_refresh_token)) {
                    $hasGoogleConnection = true;
                } else if ($isDemo && session('google_connected') && (session('google_refresh_token') || session('google_access_token'))) {
                    $hasGoogleConnection = true;
                    $googleRefreshToken = session('google_refresh_token');
                    $googleAccessToken = session('google_access_token');
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
                                if ($aUser && $this->shouldSendMailTo($aUser->email)) {
                                    $attendees[] = $aUser->email;
                                }
                            }
                        }
                    }

                    // Include account manager
                    if (!empty($training->account_manager_id)) {
                        $accMgr = User::find($training->account_manager_id);
                        if ($accMgr && $this->shouldSendMailTo($accMgr->email)) $attendees[] = $accMgr->email;
                    }

                    // Include driver coordinators (comma-separated IDs)
                    foreach ($this->collectUserEmailsFromIds($request->coordinator_to_notify_list) as $coordinatorEmail) {
                        $attendees[] = $coordinatorEmail;
                    }

                    // Insert event on connected user's primary calendar and send invites
                    $trainingInfo = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])
                                ->find($training->id);

                    // Use schedule created earlier (pass refresh token/access token for demo mode)
                    $createdEventId = $googleService->createEvent($currentUser ?? (object)[], $trainingInfo, $schedule, $attendees, $googleRefreshToken, $googleAccessToken);

                    if ($createdEventId) {
                        $schedule->google_event_id = $createdEventId;
                        $schedule->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Google Calendar sync failed: ' . $e->getMessage());
            }

            // ✅ Check if facilitator_id exists before querying and send notifications
            if (!empty($request->facilitator_id)) {
                $facilitator = User::find($request->facilitator_id);

                // Refresh training with relations for passing to mailers
                $trainingInfo = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
                            ->find($training->id);

                \Log::info('Preparing to send facilitator & assistant notifications', [
                    'training_id' => $training->id,
                    'facilitator_id' => $request->facilitator_id,
                    'facilitator' => $facilitator ? $facilitator->toArray() : null,
                ]);

                if ($facilitator && !empty($facilitator->email) && $this->shouldSendMailTo($facilitator->email)) {
                    try {
                        Mail::to($facilitator->email)->send(new TrainingNotificationMail($trainingInfo, $facilitator));
                        Log::info('Sent training notification to facilitator', ['to' => $facilitator->email, 'training_id' => $training->id]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send facilitator notification', [
                            'to' => $facilitator->email,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('Skipping facilitator notification due to missing/invalid email', ['facilitator' => $facilitator ? $facilitator->toArray() : null]);
                }

                // Notify assistants by email as well (if any)
                if (!empty($request->assistant)) {
                    $assistantIds = array_filter(array_map('trim', explode(',', $request->assistant)));
                    foreach ($assistantIds as $aid) {
                        if (is_numeric($aid)) {
                            $aUser = User::find((int) $aid);
                                    if ($aUser && !empty($aUser->email) && $this->shouldSendMailTo($aUser->email)) {
                                try {
                                    Mail::to($aUser->email)->send(new TrainingNotificationMail($trainingInfo, $aUser, 'Assistant'));
                                    Log::info('Sent training notification to assistant', ['to' => $aUser->email, 'assistant_id' => $aUser->id]);
                                } catch (\Exception $e) {
                                    Log::error('Failed to send assistant notification', [
                                        'to' => $aUser->email,
                                        'assistant_id' => $aUser->id ?? null,
                                        'exception' => $e->getMessage(),
                                    ]);
                                }
                            } else {
                                Log::warning('Skipping assistant notification due to missing/invalid email', ['assistant_id' => $aid]);
                            }
                        }
                    }
                }

                // Notify coordinator about driver arrangement if requested
                if ($request->boolean('notify_coordinator') && !empty($coordinatorIds)) {
                    foreach ($coordinatorIds as $coordinatorId) {
                        $coord = User::find((int) $coordinatorId);
                        if ($coord && !empty($coord->email) && $this->shouldSendMailTo($coord->email)) {
                            try {
                                Mail::to($coord->email)->send(new DriverNotificationMail($trainingInfo, $coord));
                            } catch (\Exception $e) {
                                Log::error('Failed to send coordinator driver notification', [
                                    'to' => $coord->email,
                                    'coordinator_id' => $coord->id ?? null,
                                    'exception' => $e->getMessage(),
                                ]);
                            }
                        } else {
                            Log::warning('Skipping coordinator notification due to missing/invalid email', ['coordinator_id' => $coordinatorId]);
                        }
                    }
                }
            }

            // Notify account manager by email independently of facilitator assignment
            if (!empty($request->account_manager_id)) {
                $accMgr = User::find($request->account_manager_id);
                if ($accMgr && !empty($accMgr->email)) {
                    $trainingInfo = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])
                                ->find($training->id);
                    try {
                        Mail::to($accMgr->email)->send(new TrainingNotificationMail($trainingInfo, $accMgr, 'Account Manager'));
                    } catch (\Exception $e) {
                        Log::error('Failed to send account manager notification', [
                            'to' => $accMgr->email,
                            'account_manager_id' => $accMgr->id ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
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
        $trainings = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])->get()->map(function ($training) {
            $training->assistant_names = $this->resolveAssistantNames($training->assistant ?? '');

            $coordinatorList = trim((string) ($training->coordinator_to_notify_list ?? ''));
            $coordinatorValue = $coordinatorList !== ''
                ? $coordinatorList
                : (string) ($training->coordinator_to_notify ?? '');

            $training->coordinator_names = $this->resolveCoordinatorNames($coordinatorValue);
            return $training;
        });

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
        $training = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])->find($id);

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
        // Convert empty facilitator_id and account_manager_id to null
        if ($request->filled('facilitator_id') === false || $request->input('facilitator_id') === '') {
            $request->merge(['facilitator_id' => null]);
        }
        if ($request->filled('account_manager_id') === false || $request->input('account_manager_id') === '') {
            $request->merge(['account_manager_id' => null]);
        }

        // Validate request - include driver/transportation and notifier fields
        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer'],
            'mode' => ['required', 'string', 'max:255'],
            'facilitator_id' => ['nullable', 'integer'],
            'account_manager_id' => ['nullable', 'integer'],
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
            'need_transportation' => ['nullable'],
            'outbound_pickup_time' => ['nullable'],
            'outbound_contact_number' => ['nullable','string','max:255'],
            'outbound_pickup_location' => ['nullable','string','max:255'],
            'outbound_dropoff_location' => ['nullable','string','max:255'],
            'return_trip_needed' => ['nullable'],
            'return_pickup_time' => ['nullable'],
            'return_contact_number' => ['nullable','string','max:255'],
            'return_pickup_location' => ['nullable','string','max:255'],
            'return_dropoff_location' => ['nullable','string','max:255'],
            'notify_coordinator' => ['nullable'],
            'coordinator_to_notify_list' => ['required_if:notify_coordinator,1', 'nullable', 'string'],
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

            // Update training session with new fields
            $trainingSession->update([
                'course_id' => $request->course_id,
                'mode' => $request->mode,
                'facilitator_id' => $request->facilitator_id,
                'account_manager_id' => $request->account_manager_id,
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
                'coordinator_to_notify_list' => $request->coordinator_to_notify_list,
                'coordinator_to_notify' => $this->extractCoordinatorIds($request->coordinator_to_notify_list)[0] ?? null,
                'is_updated' => !$facilitatorChanged,
            ]);

            // Update schedule
            // Prefer the schedule row that already has a google_event_id (if any),
            // otherwise fall back to the most-recent schedule record for this training.
            $schedule = Schedule::where('training_id', $trainingSession->id)->whereNotNull('google_event_id')->first();
            if (!$schedule) {
                $schedule = Schedule::where('training_id', $trainingSession->id)->orderBy('id', 'desc')->first();
            }
            // If we still don't have a schedule record, throw to keep behavior consistent
            if (!$schedule) {
                throw new \Exception('Schedule record not found for training ID ' . $trainingSession->id);
            }

            $schedule->update([
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
            ]);

            \DB::commit();

            // === Google Calendar sync: update existing event if present, otherwise create ===
            try {
                $currentUser = Auth::user();
                $isDemo = filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN);
                $googleRefreshToken = null;
                $googleAccessToken = null;

                // Determine token for real user or demo session
                $hasGoogleConnection = false;
                if ($currentUser && !empty($currentUser->google_refresh_token)) {
                    $hasGoogleConnection = true;
                } else if ($isDemo && session('google_connected')) {
                    $hasGoogleConnection = true;
                    $googleRefreshToken = session('google_refresh_token');
                    $googleAccessToken = session('google_access_token');
                }

                if ($hasGoogleConnection) {
                    $googleService = new GoogleCalendarService();

                    // Build attendees list
                    $attendees = [];
                    if (!empty($trainingSession->facilitator_id)) {
                        $fac = User::find($trainingSession->facilitator_id);
                        if ($fac && !empty($fac->email)) $attendees[] = $fac->email;
                    }
                    if (!empty($trainingSession->account_manager_id)) {
                        $accMgr = User::find($trainingSession->account_manager_id);
                        if ($accMgr && $this->shouldSendMailTo($accMgr->email)) $attendees[] = $accMgr->email;
                    }
                    if (!empty($trainingSession->assistant)) {
                        $assistantIds = array_filter(array_map('trim', explode(',', $trainingSession->assistant)));
                        foreach ($assistantIds as $aid) {
                            if (is_numeric($aid)) {
                                $aUser = User::find((int) $aid);
                                if ($aUser && $this->shouldSendMailTo($aUser->email)) $attendees[] = $aUser->email;
                            }
                        }
                    }

                    foreach ($this->collectUserEmailsFromIds($trainingSession->coordinator_to_notify_list ?? $trainingSession->coordinator_to_notify ?? '') as $coordinatorEmail) {
                        $attendees[] = $coordinatorEmail;
                    }

                    // Refresh training with relations
                    $trainingInfo = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])
                                    ->find($trainingSession->id);

                    if (!empty($schedule->google_event_id)) {
                        $updatedEventId = $googleService->updateEvent($currentUser ?? (object)[], $schedule->google_event_id, $trainingInfo, $schedule, $attendees, $googleRefreshToken, $googleAccessToken);
                        if ($updatedEventId) {
                            $schedule->google_event_id = $updatedEventId;
                            $schedule->save();
                        }
                    } else {
                        // Try to find an existing event in the user's calendar that matches this training
                        $foundEventId = $googleService->findEventId($currentUser ?? (object)[], $trainingInfo, $schedule, $googleRefreshToken, $googleAccessToken);
                        if (!empty($foundEventId)) {
                            // Use found event id to update instead of creating a duplicate
                            $updatedEventId = $googleService->updateEvent($currentUser ?? (object)[], $foundEventId, $trainingInfo, $schedule, $attendees, $googleRefreshToken, $googleAccessToken);
                            if ($updatedEventId) {
                                $schedule->google_event_id = $updatedEventId;
                                $schedule->save();
                            }
                        } else {
                            $createdEventId = $googleService->createEvent($currentUser ?? (object)[], $trainingInfo, $schedule, $attendees, $googleRefreshToken, $googleAccessToken);
                            if ($createdEventId) {
                                $schedule->google_event_id = $createdEventId;
                                $schedule->save();
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Google Calendar sync failed during update: ' . $e->getMessage());
            }

            // === Email Notification Logic ===
            if ($facilitatorChanged) {
                // Facilitator changed
                if ($previousFacilitator && !empty($previousFacilitator->email)) {
                    try {
                        Mail::to($previousFacilitator->email)
                            ->send(new TrainingReassignmentMail($trainingSession, $previousFacilitator, $newFacilitator));
                    } catch (\Exception $e) {
                        Log::error('Failed to send reassignment mail to previous facilitator', [
                            'to' => $previousFacilitator->email ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }
                if ($newFacilitator && !empty($newFacilitator->email) && $this->shouldSendMailTo($newFacilitator->email)) {
                    try {
                        Mail::to($newFacilitator->email)
                            ->send(new TrainingNotificationMail($trainingSession, $newFacilitator));
                        Log::info('Sent training notification to new facilitator (changed)', ['to' => $newFacilitator->email, 'training_id' => $trainingSession->id]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send notification mail to new facilitator', [
                            'to' => $newFacilitator->email ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }

                    // Notify assistants on facilitator change as well
                    if (!empty($trainingSession->assistant)) {
                        $assistantIds = array_filter(array_map('trim', explode(',', $trainingSession->assistant)));
                        foreach ($assistantIds as $aid) {
                            if (is_numeric($aid)) {
                                $aUser = User::find((int) $aid);
                                if ($aUser && !empty($aUser->email) && $this->shouldSendMailTo($aUser->email)) {
                                    try {
                                        Mail::to($aUser->email)->send(new TrainingNotificationMail($trainingSession, $aUser, 'Assistant'));
                                        Log::info('Sent training notification to assistant (facilitator changed)', ['to' => $aUser->email, 'assistant_id' => $aUser->id]);
                                    } catch (\Exception $e) {
                                        Log::error('Failed to send assistant notification (facilitator changed)', [
                                            'to' => $aUser->email,
                                            'assistant_id' => $aUser->id ?? null,
                                            'exception' => $e->getMessage(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            } elseif ($newFacilitator) {
                // Facilitator unchanged, send update email
                if (!empty($newFacilitator->email) && $this->shouldSendMailTo($newFacilitator->email)) {
                    try {
                        Mail::to($newFacilitator->email)
                            ->send(new TrainingNotificationMail($trainingSession, $newFacilitator));
                        Log::info('Sent training update notification to facilitator', ['to' => $newFacilitator->email, 'training_id' => $trainingSession->id]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send update mail to facilitator', [
                            'to' => $newFacilitator->email ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }

                    // Notify assistants on update as well
                    if (!empty($trainingSession->assistant)) {
                        $assistantIds = array_filter(array_map('trim', explode(',', $trainingSession->assistant)));
                        foreach ($assistantIds as $aid) {
                            if (is_numeric($aid)) {
                                $aUser = User::find((int) $aid);
                                if ($aUser && !empty($aUser->email) && $this->shouldSendMailTo($aUser->email)) {
                                    try {
                                        Mail::to($aUser->email)->send(new TrainingNotificationMail($trainingSession, $aUser, 'Assistant'));
                                        Log::info('Sent training update notification to assistant', ['to' => $aUser->email, 'assistant_id' => $aUser->id]);
                                    } catch (\Exception $e) {
                                        Log::error('Failed to send assistant notification (update)', [
                                            'to' => $aUser->email,
                                            'assistant_id' => $aUser->id ?? null,
                                            'exception' => $e->getMessage(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                // Reset is_updated back to false after sending the email
                $trainingSession->update(['is_updated' => false]);
            } elseif ($previousFacilitator && !$newFacilitator) {
                // Facilitator removed, notify previous facilitator
                if ($previousFacilitator && !empty($previousFacilitator->email)) {
                    try {
                        Mail::to($previousFacilitator->email)
                            ->send(new TrainingReassignmentMail($trainingSession, $previousFacilitator, null));
                    } catch (\Exception $e) {
                        Log::error('Failed to send reassignment mail (facilitator removed)', [
                            'to' => $previousFacilitator->email ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }
            }

            // Notify coordinator about driver arrangement on update if configured
            try {
                $updatedCoordinatorIds = $this->extractCoordinatorIds($trainingSession->coordinator_to_notify_list ?? $trainingSession->coordinator_to_notify ?? '');
                if ($trainingSession->notify_coordinator && !empty($updatedCoordinatorIds)) {
                        foreach ($updatedCoordinatorIds as $coordinatorId) {
                            $coord = User::find((int) $coordinatorId);
                            if ($coord && $this->shouldSendMailTo($coord->email)) {
                                try {
                                    Mail::to($coord->email)->send(new DriverNotificationMail($trainingSession, $coord, true));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send coordinator driver notification (on update)', [
                                        'to' => $coord->email,
                                        'coordinator_id' => $coord->id ?? null,
                                        'exception' => $e->getMessage(),
                                    ]);
                                }
                            }
                        }
                }
            } catch (\Exception $e) {
                Log::error('Error during coordinator notification (on update): ' . $e->getMessage());
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

            if ($trainingSession->facilitator_id) {
                $facilitator = User::find($trainingSession->facilitator_id);

                if ($facilitator && $this->shouldSendMailTo($facilitator->email)) {
                    try {
                        Mail::to($facilitator->email)->send(new CancelledTrainingMail($trainingSession, $facilitator, 'Facilitator'));
                    } catch (\Exception $e) {
                        Log::error('Failed to send cancellation mail to facilitator', [
                            'to' => $facilitator->email ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('Skipping facilitator cancellation mail due to missing/invalid email', ['facilitator_id' => $trainingSession->facilitator_id]);
                }
            }

            // Notify account manager if assigned
            if (!empty($trainingSession->account_manager_id)) {
                $accMgr = User::find($trainingSession->account_manager_id);
                if ($accMgr && $this->shouldSendMailTo($accMgr->email)) {
                    try {
                        Mail::to($accMgr->email)->send(new CancelledTrainingMail($trainingSession, $accMgr, 'Account Manager'));
                    } catch (\Exception $e) {
                        Log::error('Failed to send cancellation mail to account manager', [
                            'to' => $accMgr->email ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('Skipping account manager cancellation mail due to missing/invalid email', ['account_manager_id' => $trainingSession->account_manager_id]);
                }
            }

            // Notify assistants (assistant field contains comma-separated user ids)
            if (!empty($trainingSession->assistant)) {
                $assistantIds = array_filter(array_map('trim', explode(',', $trainingSession->assistant)));
                foreach ($assistantIds as $aid) {
                    if (!is_numeric($aid)) continue;
                    $aUser = User::find((int) $aid);
                    if ($aUser && $this->shouldSendMailTo($aUser->email)) {
                        try {
                            Mail::to($aUser->email)->send(new CancelledTrainingMail($trainingSession, $aUser, 'Assistant'));
                        } catch (\Exception $e) {
                            Log::error('Failed to send cancellation mail to assistant', [
                                'to' => $aUser->email ?? null,
                                'assistant_id' => $aUser->id ?? null,
                                'exception' => $e->getMessage(),
                            ]);
                        }
                    } else {
                        Log::warning('Skipping assistant cancellation mail due to missing/invalid email', ['assistant_id' => $aid]);
                    }
                }
            }

            $this->sendDriverCancellationNotifications($trainingSession);

            // Prefer the schedule row that already has a google_event_id (if any), otherwise fall back to the most-recent schedule record for this training.
            $schedule = Schedule::where('training_id', $trainingSession->id)->whereNotNull('google_event_id')->first();
            if (!$schedule) {
                $schedule = Schedule::where('training_id', $trainingSession->id)->orderBy('id', 'desc')->first();
            }

            if ($schedule) {
                // Attempt to delete the event from Google Calendar if it exists
                try {
                    $currentUser = Auth::user();
                    $isDemo = filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN);
                    $googleRefreshToken = null;
                    $googleAccessToken = null;

                    $hasGoogleConnection = false;
                    if ($currentUser && !empty($currentUser->google_refresh_token)) {
                        $hasGoogleConnection = true;
                    } elseif ($isDemo && session('google_connected') && (session('google_refresh_token') || session('google_access_token'))) {
                        $hasGoogleConnection = true;
                        $googleRefreshToken = session('google_refresh_token');
                        $googleAccessToken = session('google_access_token');
                    }

                    if (!$hasGoogleConnection) {
                        Log::info('No Google connection available to delete event for training', ['training_id' => $trainingSession->id]);
                    }

                    $eventIdToDelete = $schedule->google_event_id;

                    if (empty($eventIdToDelete) && $hasGoogleConnection) {
                        $trainingInfo = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])
                            ->find($trainingSession->id);

                        $eventIdToDelete = (new GoogleCalendarService())->findEventId(
                            $currentUser ?? (object)[],
                            $trainingInfo,
                            $schedule,
                            $googleRefreshToken,
                            $googleAccessToken
                        );
                    }

                    if (!empty($eventIdToDelete)) {
                        if ($hasGoogleConnection) {
                            $googleService = new GoogleCalendarService();
                            $deleted = $googleService->deleteEvent($currentUser ?? (object)[], $eventIdToDelete, $googleRefreshToken, $googleAccessToken);
                            if ($deleted) {
                                Log::info('Deleted Google Calendar event for schedule', ['schedule_id' => $schedule->id, 'eventId' => $eventIdToDelete]);
                            } else {
                                Log::warning('Failed to delete Google Calendar event for schedule', ['schedule_id' => $schedule->id, 'eventId' => $eventIdToDelete]);
                            }
                        } else {
                            Log::warning('Schedule has google_event_id but no google connection available to delete it', ['schedule_id' => $schedule->id, 'eventId' => $eventIdToDelete]);
                        }
                    } else {
                        Log::info('No google_event_id present on selected schedule; skipping calendar delete', ['schedule_id' => $schedule->id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error deleting google calendar event: ' . $e->getMessage());
                }

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


