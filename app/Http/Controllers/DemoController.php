<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\Account;
use App\Models\Company;
use App\Models\Course;
use App\Models\Training;
use App\Models\Schedule;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\TrainingController;
use App\Mail\DriverNotificationMail;
use App\Mail\TrainingNotificationMail;

class DemoController extends Controller
{
    private function isEnabled(): bool
    {
        return filter_var(env('APP_DEMO', false), FILTER_VALIDATE_BOOLEAN);
    }

    private function fixtures(string $file): array
    {
        $path = public_path('mock/' . $file);

        if (!file_exists($path)) {
            return [];
        }

        $data = json_decode((string) file_get_contents($path), true);

        return is_array($data) ? $data : [];
    }

    private function fixtureCollection(string $file): Collection
    {
        return collect($this->fixtures($file))->map(fn (array $row) => (object) $row);
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

    private function extractCoordinatorIds(?string $coordinatorValue): array
    {
        if (trim((string) $coordinatorValue) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $coordinatorValue))));
    }

    private function resolveCoordinatorUsers(?string $coordinatorValue): Collection
    {
        $coordinatorIds = $this->extractCoordinatorIds($coordinatorValue);

        if (empty($coordinatorIds)) {
            return collect();
        }

        return User::whereIn('id', array_map('intval', $coordinatorIds))->get();
    }

    private function findUser(int $id): ?array
    {
        return collect($this->fixtures('users.json'))->firstWhere('id', $id);
    }

    private function syncCourseFromFixture(?int $requestedId): ?int
    {
        if (!$requestedId) {
            return null;
        }

        $fixture = collect($this->fixtures('courses.json'))->firstWhere('id', $requestedId);

        if (!$fixture) {
            return Course::whereKey($requestedId)->exists() ? $requestedId : (int) (Course::query()->value('id') ?? 0);
        }

        DB::table('course')->updateOrInsert(
            ['id' => $requestedId],
            [
                'course_code' => $fixture['course_code'] ?? null,
                'course_name' => $fixture['course_name'] ?? ('Course ' . $requestedId),
                'description' => $fixture['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return $requestedId;
    }

    private function syncCompanyFromFixture(?int $requestedId): ?int
    {
        if (!$requestedId) {
            return null;
        }

        $fixture = collect($this->fixtures('companies.json'))->firstWhere('id', $requestedId);

        if (!$fixture) {
            return Company::whereKey($requestedId)->exists() ? $requestedId : (int) (Company::query()->value('id') ?? 0);
        }

        DB::table('company')->updateOrInsert(
            ['id' => $requestedId],
            [
                'company_name' => $fixture['company_name'] ?? ('Company ' . $requestedId),
                'contact_person' => $fixture['contact_person'] ?? null,
                'contact_number' => $fixture['contact_number'] ?? null,
                'email' => $fixture['email'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return $requestedId;
    }

    private function syncUserFromFixture(?int $requestedId): ?int
    {
        if (!$requestedId) {
            return null;
        }

        $fixture = collect($this->fixtures('users.json'))->firstWhere('id', $requestedId);

        if (!$fixture) {
            return User::whereKey($requestedId)->exists() ? $requestedId : (int) (User::query()->value('id') ?? 0);
        }

        DB::table('users')->updateOrInsert(
            ['id' => $requestedId],
            [
                'name' => $fixture['name'] ?? ('User ' . $requestedId),
                'username' => $fixture['username'] ?? ('user' . $requestedId),
                'email' => $fixture['email'] ?? ('user' . $requestedId . '@example.com'),
                'usertype' => $fixture['usertype'] ?? 'facilitator',
                'color' => $fixture['color'] ?? '#808080',
                'contact_number' => $fixture['contact_number'] ?? null,
                'image' => $fixture['image'] ?? null,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return $requestedId;
    }

    private function syncAccountFromFixture(?int $requestedId): ?int
    {
        if (!$requestedId) {
            return null;
        }

        $fixture = collect($this->fixtures('accounts.json'))->firstWhere('id', $requestedId);

        if (!$fixture) {
            return Account::whereKey($requestedId)->exists() ? $requestedId : (int) (Account::query()->value('id') ?? 0);
        }

        DB::table('credentials')->updateOrInsert(
            ['id' => $requestedId],
            [
                'account_email' => $fixture['account_email'] ?? ('account' . $requestedId . '@example.com'),
                'account_password' => $fixture['account_password'] ?? 'password123',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return $requestedId;
    }

    private function resolveCourseId(?int $requestedId): int
    {
        if ($requestedId && Course::whereKey($requestedId)->exists()) {
            return $requestedId;
        }

        return (int) (Course::query()->value('id') ?? Course::query()->create([
            'course_code' => 'DEMO-001',
            'course_name' => 'Demo Course',
            'description' => 'Auto-created demo course',
        ])->id);
    }

    private function resolveNullableUserId(?int $requestedId): ?int
    {
        if ($requestedId && User::whereKey($requestedId)->exists()) {
            return $requestedId;
        }

        return User::query()->value('id');
    }

    private function resolveNullableCompanyId(?int $requestedId): ?int
    {
        if ($requestedId && Company::whereKey($requestedId)->exists()) {
            return $requestedId;
        }

        return Company::query()->value('id');
    }

    private function resolveNullableAccountId(?int $requestedId): ?int
    {
        if ($requestedId && Account::whereKey($requestedId)->exists()) {
            return $requestedId;
        }

        return Account::query()->value('id');
    }

    public function home()
    {
        return view('login.login');
    }

    public function calendar(): View
    {
        return view('main-content.calendar');
    }

    public function addTrainingForm(): View
    {
        return view('add_training.add_training', [
            'users' => $this->fixtureCollection('users.json'),
            'courses' => $this->fixtureCollection('courses.json'),
            'companies' => $this->fixtureCollection('companies.json'),
            'accounts' => $this->fixtureCollection('accounts.json'),
        ]);
    }

    public function storeTraining(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $courseId = $this->syncCourseFromFixture($request->integer('course_id'));
            $facilitatorId = $this->syncUserFromFixture($request->integer('facilitator_id'));
            $accountManagerId = $this->syncUserFromFixture($request->integer('account_manager_id'));
            $companyId = $this->syncCompanyFromFixture($request->integer('company_id'));
            $accountId = $this->syncAccountFromFixture($request->integer('account_id'));

            if (!$courseId) {
                $courseId = $this->resolveCourseId($request->integer('course_id'));
            }
            if (!$facilitatorId) {
                $facilitatorId = $this->resolveNullableUserId($request->integer('facilitator_id'));
            }
            if (!$accountManagerId) {
                $accountManagerId = $this->resolveNullableUserId($request->integer('account_manager_id'));
            }
            if (!$companyId) {
                $companyId = $this->resolveNullableCompanyId($request->integer('company_id'));
            }
            if (!$accountId) {
                $accountId = $this->resolveNullableAccountId($request->integer('account_id'));
            }

            // Ensure requested IDs exist - create demo records if needed
            $reqCourse = $request->integer('course_id');
            if ($reqCourse && !Course::whereKey($reqCourse)->exists()) {
                DB::table('course')->updateOrInsert(['id' => $reqCourse], [
                    'course_code' => 'DEMO-' . $reqCourse,
                    'course_name' => 'Demo Course ' . $reqCourse,
                    'description' => 'Auto-created',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $courseId = $reqCourse;
            }

            $reqFacilitator = $request->integer('facilitator_id');
            if ($reqFacilitator && !User::whereKey($reqFacilitator)->exists()) {
                DB::table('users')->updateOrInsert(['id' => $reqFacilitator], [
                    'name' => 'Demo Facilitator ' . $reqFacilitator,
                    'email' => 'facilitator' . $reqFacilitator . '@example.com',
                    'username' => 'facilitator' . $reqFacilitator,
                    'password' => Hash::make('password123'),
                    'usertype' => 'facilitator',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $facilitatorId = $reqFacilitator;
            }

            $reqAccountManager = $request->integer('account_manager_id');
            if ($reqAccountManager && !User::whereKey($reqAccountManager)->exists()) {
                DB::table('users')->updateOrInsert(['id' => $reqAccountManager], [
                    'name' => 'Demo Account Manager ' . $reqAccountManager,
                    'email' => 'accountmgr' . $reqAccountManager . '@example.com',
                    'username' => 'accountmgr' . $reqAccountManager,
                    'password' => Hash::make('password123'),
                    'usertype' => 'coordinator',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $accountManagerId = $reqAccountManager;
            }

            $reqCompany = $request->integer('company_id');
            if ($reqCompany && !Company::whereKey($reqCompany)->exists()) {
                DB::table('company')->updateOrInsert(['id' => $reqCompany], [
                    'company_name' => 'Demo Company ' . $reqCompany,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $companyId = $reqCompany;
            }

            // Ensure coordinator_to_notify users exist if specified (for driver arrangement)
            $coordinatorIds = $this->extractCoordinatorIds(
                $request->input('coordinator_to_notify_list', $request->input('coordinator_to_notify', ''))
            );
            foreach ($coordinatorIds as $coordinatorId) {
                if (is_numeric($coordinatorId) && !User::whereKey((int) $coordinatorId)->exists()) {
                    DB::table('users')->updateOrInsert(['id' => (int) $coordinatorId], [
                        'name' => 'Demo Coordinator ' . $coordinatorId,
                        'email' => 'coordinator' . $coordinatorId . '@example.com',
                        'username' => 'coordinator' . $coordinatorId,
                        'password' => Hash::make('password123'),
                        'usertype' => 'coordinator',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Ensure assistant user IDs exist if specified (comma-separated)
            $assistantStr = $request->string('assistant', '');
            if (!empty($assistantStr)) {
                $assistantIds = array_filter(array_map('trim', explode(',', $assistantStr)));
                foreach ($assistantIds as $assistantId) {
                    if (is_numeric($assistantId)) {
                        $numId = (int) $assistantId;
                        if (!User::whereKey($numId)->exists()) {
                            DB::table('users')->updateOrInsert(['id' => $numId], [
                                'name' => 'Demo Assistant ' . $numId,
                                'email' => 'assistant' . $numId . '@example.com',
                                'username' => 'assistant' . $numId,
                                'password' => Hash::make('password123'),
                                'usertype' => 'facilitator',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                }
            }

            $reqAccount = $request->integer('account_id');
            if ($reqAccount && !Account::whereKey($reqAccount)->exists()) {
                DB::table('credentials')->updateOrInsert(['id' => $reqAccount], [
                    'account_email' => 'account' . $reqAccount . '@example.com',
                    'account_password' => 'password123',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $accountId = $reqAccount;
            }

            // Create the Training record
            $training = Training::create([
                'course_id' => $courseId,
                'mode' => $request->mode,
                'facilitator_id' => $facilitatorId,
                'account_manager_id' => $accountManagerId,
                'location' => $request->location,
                'platform' => $request->platform,
                'conference_link' => $request->conference_link,
                'company_id' => $companyId,
                'assistant' => $request->assistant,
                'account_id' => $accountId,
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
                'coordinator_to_notify_list' => implode(', ', $coordinatorIds),
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

            DB::commit();

            // Create Google Calendar event if Google is connected in demo session
            try {
                $googleRefreshToken = null;
                $googleAccessToken = null;
                $hasGoogleConnection = false;
                
                // Check if Google is connected via session (demo mode)
                if (session('google_connected') && session('google_refresh_token')) {
                    $hasGoogleConnection = true;
                    $googleRefreshToken = session('google_refresh_token');
                    $googleAccessToken = session('google_access_token');
                } elseif (session('google_connected') && session('google_access_token')) {
                    $hasGoogleConnection = true;
                    $googleAccessToken = session('google_access_token');
                }
                
                if ($hasGoogleConnection) {
                    $googleService = new GoogleCalendarService();

                    // Build attendees list: include facilitator if available and has email
                    $attendees = [];
                    if (!empty($facilitatorId)) {
                        $fac = User::find($facilitatorId);
                        if ($fac && !empty($fac->email)) {
                            $attendees[] = $fac->email;
                        }
                    }

                    // Include account manager as invitee
                    if (!empty($accountManagerId)) {
                        $accMgr = User::find($accountManagerId);
                        if ($accMgr && !empty($accMgr->email)) {
                            $attendees[] = $accMgr->email;
                        }
                    }

                    // Include assistants (comma-separated IDs) if any
                    if (!empty($training->assistant)) {
                        $assistantIds = array_filter(array_map('trim', explode(',', $training->assistant)));
                        foreach ($assistantIds as $aid) {
                            if (is_numeric($aid)) {
                                $aUser = User::find((int) $aid);
                                if ($aUser && !empty($aUser->email)) {
                                    $attendees[] = $aUser->email;
                                }
                            }
                        }
                    }

                    // Fetch the full training with relationships
                    $trainingInfo = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])
                                ->find($training->id);

                    // Create event with refresh token (demo mode passes token from session)
                    // Create a demo user object for GoogleCalendarService
                    $demoUser = (object) ['id' => 0, 'email' => 'demo@example.com'];
                    $createdEvent = $googleService->createEvent($demoUser, $trainingInfo, $schedule, $attendees, $googleRefreshToken, $googleAccessToken);

                    if ($createdEvent === false) {
                        Log::warning('Google Calendar sync did not create an event in demo mode', [
                            'training_id' => $training->id,
                            'has_refresh_token' => !empty($googleRefreshToken),
                            'has_access_token' => !empty($googleAccessToken),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Google Calendar sync failed in demo mode: ' . $e->getMessage());
            }

            // Notify account manager on assignment (demo mode)
            if (!empty($training->account_manager_id)) {
                $trainingInfo = Training::with(['schedule', 'facilitator', 'account_manager', 'course', 'company', 'account'])
                            ->find($training->id);
                $accountManager = User::find((int) $training->account_manager_id);

                if ($accountManager && !empty($accountManager->email)) {
                    try {
                        Mail::to($accountManager->email)
                            ->send(new TrainingNotificationMail($trainingInfo, $accountManager, 'Account Manager'));
                    } catch (\Exception $e) {
                        Log::error('Failed to send account manager assignment mail (demo)', [
                            'to' => $accountManager->email,
                            'account_manager_id' => $accountManager->id ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }
            }

            // Notify coordinator about driver arrangement if requested (demo mode)
            if ($request->boolean('notify_coordinator') && !empty($coordinatorIds)) {
                $trainingInfo = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
                            ->find($training->id);
                foreach ($coordinatorIds as $coordinatorId) {
                    $coord = User::find((int) $coordinatorId);
                    if (!$coord || empty($coord->email)) {
                        continue;
                    }

                    try {
                        Mail::to($coord->email)->send(new DriverNotificationMail($trainingInfo, $coord));
                    } catch (\Exception $e) {
                        Log::error('Failed to send coordinator driver notification (demo)', [
                            'to' => $coord->email,
                            'coordinator_id' => $coord->id ?? null,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }
            }

            return response()->json([
                'message' => 'Training created successfully',
                'training' => [
                    'id' => $training->id,
                    'course_id' => (int) $training->course_id,
                    'mode' => (string) $training->mode,
                    'facilitator_id' => $training->facilitator_id,
                    'company_id' => $training->company_id,
                    'account_id' => $training->account_id,
                ],
                'schedule' => [
                    'from_date' => (string) $schedule->from_date,
                    'to_date' => (string) $schedule->to_date,
                    'from_time' => (string) $schedule->from_time,
                    'to_time' => (string) $schedule->to_time,
                ],
                'demo' => true,
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if (strpos($e->getMessage(), 'FOREIGN KEY') !== false) {
                Log::error('Foreign key constraint violation in demo mode: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Foreign key constraint violation',
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                ], 422);
            }
            Log::error('Database query failed in demo mode: ' . $e->getMessage());
            return response()->json([
                'message' => 'Training creation failed',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Training creation failed in demo mode: ' . $e->getMessage());
            return response()->json([
                'message' => 'Training creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTraining(): JsonResponse
    {
        // In demo mode, fetch from database (includes newly created events)
        // but supplement with fixtures if DB is empty
        if ($this->isEnabled()) {
            $trainings = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
                ->get();

            // If no trainings in DB, use fixtures
            if ($trainings->isEmpty()) {
                $fixtures = $this->fixtures('trainings.json');
                $trainings = collect($fixtures)->map(function ($t) {
                    return (object) [
                        'id' => $t['id'] ?? null,
                        'course_id' => $t['course']['id'] ?? null,
                        'mode' => $t['mode'] ?? 'virtual',
                        'platform' => $t['platform'] ?? ($t['location'] ?? null),
                        'conference_link' => $t['conference_link'] ?? null,
                        'location' => $t['location'] ?? null,
                        'assistant' => $t['assistant'] ?? null,
                        'facilitator_id' => $t['facilitator_id'] ?? null,
                        'company_id' => $t['company']['id'] ?? null,
                        'account_id' => $t['account']['id'] ?? null,
                        'need_transportation' => $t['need_transportation'] ?? false,
                        'outbound_pickup_time' => $t['outbound_pickup_time'] ?? null,
                        'outbound_contact_number' => $t['outbound_contact_number'] ?? null,
                        'outbound_pickup_location' => $t['outbound_pickup_location'] ?? null,
                        'outbound_dropoff_location' => $t['outbound_dropoff_location'] ?? null,
                        'return_trip_needed' => $t['return_trip_needed'] ?? false,
                        'return_pickup_time' => $t['return_pickup_time'] ?? null,
                        'return_contact_number' => $t['return_contact_number'] ?? null,
                        'return_pickup_location' => $t['return_pickup_location'] ?? null,
                        'return_dropoff_location' => $t['return_dropoff_location'] ?? null,
                        'notify_coordinator' => $t['notify_coordinator'] ?? false,
                        'coordinator_to_notify_list' => $t['coordinator_to_notify_list'] ?? ($t['coordinator_to_notify'] ?? null),
                        'coordinator_to_notify' => $t['coordinator_to_notify'] ?? null,
                        'course' => $t['course'] ?? null,
                        'facilitator' => $t['facilitator'] ?? null,
                        'company' => $t['company'] ?? null,
                        'account' => $t['account'] ?? null,
                        'schedule' => $t['schedule'] ?? null,
                    ];
                });
            }

            // Format response
            $formattedTrainings = $trainings->map(function ($training) {
                return [
                    'id' => $training->id ?? null,
                    'course_id' => $training->course_id ?? ($training->course['id'] ?? null),
                    'mode' => $training->mode ?? 'virtual',
                    'platform' => $training->platform ?? null,
                    'conference_link' => $training->conference_link ?? null,
                    'location' => $training->location ?? null,
                    'assistant' => $training->assistant ?? null,
                    'assistant_names' => $this->resolveAssistantNames($training->assistant ?? ''),
                    'facilitator_id' => $training->facilitator_id ?? null,
                    'company_id' => $training->company_id ?? null,
                    'account_id' => $training->account_id ?? null,
                    'need_transportation' => $training->need_transportation ?? false,
                    'outbound_pickup_time' => $training->outbound_pickup_time ?? null,
                    'outbound_contact_number' => $training->outbound_contact_number ?? null,
                    'outbound_pickup_location' => $training->outbound_pickup_location ?? null,
                    'outbound_dropoff_location' => $training->outbound_dropoff_location ?? null,
                    'return_trip_needed' => $training->return_trip_needed ?? false,
                    'return_pickup_time' => $training->return_pickup_time ?? null,
                    'return_contact_number' => $training->return_contact_number ?? null,
                    'return_pickup_location' => $training->return_pickup_location ?? null,
                    'return_dropoff_location' => $training->return_dropoff_location ?? null,
                    'notify_coordinator' => $training->notify_coordinator ?? false,
                    'coordinator_to_notify_list' => $training->coordinator_to_notify_list ?? ($training->coordinator_to_notify ?? null),
                    'coordinator_to_notify' => $training->coordinator_to_notify ?? null,
                    'coordinator_names' => $this->resolveCoordinatorNames($training->coordinator_to_notify_list ?? ($training->coordinator_to_notify ?? '')),
                    'course' => is_object($training->course) ? [
                        'id' => $training->course->id,
                        'course_code' => $training->course->course_code,
                        'course_name' => $training->course->course_name,
                        'description' => $training->course->description ?? null,
                    ] : ($training->course ?? null),
                    'facilitator' => is_object($training->facilitator) ? [
                        'id' => $training->facilitator->id,
                        'name' => $training->facilitator->name,
                        'email' => $training->facilitator->email,
                        'color' => $training->facilitator->color,
                        'image' => $training->facilitator->image ?? null,
                    ] : ($training->facilitator ?? null),
                    'company' => is_object($training->company) ? [
                        'id' => $training->company->id,
                        'company_name' => $training->company->company_name,
                        'contact_person' => $training->company->contact_person ?? null,
                        'contact_number' => $training->company->contact_number ?? null,
                        'email' => $training->company->email ?? null,
                    ] : ($training->company ?? null),
                    'account' => is_object($training->account) ? [
                        'id' => $training->account->id,
                        'account_email' => $training->account->account_email,
                    ] : ($training->account ?? null),
                    'schedule' => is_object($training->schedule) ? [
                        'from_date' => (string) $training->schedule->from_date,
                        'to_date' => (string) $training->schedule->to_date,
                        'from_time' => (string) $training->schedule->from_time,
                        'to_time' => (string) $training->schedule->to_time,
                    ] : ($training->schedule ?? null),
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'data' => $formattedTrainings,
            ], 200);
        }

        // Fetch trainings from database when not in demo mode
        $trainings = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
            ->get()
            ->map(function ($training) {
                return [
                    'id' => $training->id,
                    'course_id' => $training->course_id,
                    'mode' => $training->mode,
                    'platform' => $training->platform,
                    'conference_link' => $training->conference_link,
                    'location' => $training->location,
                    'assistant' => $training->assistant,
                    'assistant_names' => $this->resolveAssistantNames($training->assistant ?? ''),
                    'facilitator_id' => $training->facilitator_id,
                    'company_id' => $training->company_id,
                    'account_id' => $training->account_id,
                    'need_transportation' => $training->need_transportation,
                    'outbound_pickup_time' => $training->outbound_pickup_time,
                    'outbound_contact_number' => $training->outbound_contact_number,
                    'outbound_pickup_location' => $training->outbound_pickup_location,
                    'outbound_dropoff_location' => $training->outbound_dropoff_location,
                    'return_trip_needed' => $training->return_trip_needed,
                    'return_pickup_time' => $training->return_pickup_time,
                    'return_contact_number' => $training->return_contact_number,
                    'return_pickup_location' => $training->return_pickup_location,
                    'return_dropoff_location' => $training->return_dropoff_location,
                    'notify_coordinator' => $training->notify_coordinator,
                    'coordinator_to_notify_list' => $training->coordinator_to_notify_list ?? ($training->coordinator_to_notify ?? null),
                    'coordinator_to_notify' => $training->coordinator_to_notify,
                    'coordinator_names' => $this->resolveCoordinatorNames($training->coordinator_to_notify_list ?? ($training->coordinator_to_notify ?? '')),
                    'course' => $training->course ? [
                        'id' => $training->course->id,
                        'course_code' => $training->course->course_code,
                        'course_name' => $training->course->course_name,
                        'description' => $training->course->description,
                    ] : null,
                    'facilitator' => $training->facilitator ? [
                        'id' => $training->facilitator->id,
                        'name' => $training->facilitator->name,
                        'email' => $training->facilitator->email,
                        'color' => $training->facilitator->color,
                        'image' => $training->facilitator->image,
                    ] : null,
                    'company' => $training->company ? [
                        'id' => $training->company->id,
                        'company_name' => $training->company->company_name,
                        'contact_person' => $training->company->contact_person,
                        'contact_number' => $training->company->contact_number,
                        'email' => $training->company->email,
                    ] : null,
                    'account' => $training->account ? [
                        'id' => $training->account->id,
                        'account_email' => $training->account->account_email,
                    ] : null,
                    'schedule' => $training->schedule ? [
                        'from_date' => (string) $training->schedule->from_date,
                        'to_date' => (string) $training->schedule->to_date,
                        'from_time' => (string) $training->schedule->from_time,
                        'to_time' => (string) $training->schedule->to_time,
                    ] : null,
                ];
            })
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $trainings,
        ], 200);
    }

    public function editTraining(int $id): View
    {
        $trainingRecord = null;

        if ($this->isEnabled()) {
            $trainingRecord = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])->find($id);
        }

        $training = null;

        if ($trainingRecord) {
            $assistantTokens = array_values(array_filter(array_map('trim', explode(',', (string) ($trainingRecord->assistant ?? '')))));
            $assistantUsers = collect();

            if (!empty($assistantTokens)) {
                $numericAssistantIds = array_values(array_filter($assistantTokens, 'is_numeric'));

                if (!empty($numericAssistantIds)) {
                    $assistantUsers = User::whereIn('id', $numericAssistantIds)->get();
                }

                if ($assistantUsers->isEmpty()) {
                    $assistantUsers = $this->fixtureCollection('users.json')->filter(function ($user) use ($assistantTokens) {
                        $assistantName = strtolower(trim((string) ($user->name ?? '')));
                        return in_array($assistantName, array_map('strtolower', $assistantTokens), true);
                    })->values();
                }
            }

            $needsTransportation = $trainingRecord->need_transportation;

            $training = [
                'id' => $trainingRecord->id,
                'mode' => $trainingRecord->mode,
                'platform' => $trainingRecord->platform,
                'conference_link' => $trainingRecord->conference_link,
                'location' => $trainingRecord->location,
                'assistant' => $trainingRecord->assistant,
                'need_transportation' => $trainingRecord->need_transportation,
                'outbound_pickup_time' => $trainingRecord->outbound_pickup_time,
                'outbound_contact_number' => $trainingRecord->outbound_contact_number,
                'outbound_pickup_location' => $trainingRecord->outbound_pickup_location,
                'outbound_dropoff_location' => $trainingRecord->outbound_dropoff_location,
                'return_trip_needed' => $trainingRecord->return_trip_needed,
                'return_pickup_time' => $trainingRecord->return_pickup_time,
                'return_contact_number' => $trainingRecord->return_contact_number,
                'return_pickup_location' => $trainingRecord->return_pickup_location,
                'return_dropoff_location' => $trainingRecord->return_dropoff_location,
                'notify_coordinator' => $trainingRecord->notify_coordinator,
                'coordinator_to_notify_list' => $trainingRecord->coordinator_to_notify_list ?? ($trainingRecord->coordinator_to_notify ?? null),
                'coordinator_to_notify' => $trainingRecord->coordinator_to_notify,
                'course' => $trainingRecord->course ? [
                    'id' => $trainingRecord->course->id,
                    'course_code' => $trainingRecord->course->course_code,
                    'course_name' => $trainingRecord->course->course_name,
                    'description' => $trainingRecord->course->description,
                ] : null,
                'company' => $trainingRecord->company ? [
                    'id' => $trainingRecord->company->id,
                    'company_name' => $trainingRecord->company->company_name,
                    'contact_person' => $trainingRecord->company->contact_person,
                    'contact_number' => $trainingRecord->company->contact_number,
                    'email' => $trainingRecord->company->email,
                ] : null,
                'account' => $trainingRecord->account ? [
                    'id' => $trainingRecord->account->id,
                    'account_email' => $trainingRecord->account->account_email,
                    'account_password' => $trainingRecord->account->account_password ?? null,
                ] : null,
                'assistants' => $assistantUsers->map(function ($assistant) {
                    return [
                        'id' => $assistant->id,
                        'name' => $assistant->name,
                        'email' => $assistant->email ?? null,
                    ];
                })->values()->all(),
                'facilitator' => $trainingRecord->facilitator ? [
                    'id' => $trainingRecord->facilitator->id,
                    'name' => $trainingRecord->facilitator->name,
                    'email' => $trainingRecord->facilitator->email,
                    'color' => $trainingRecord->facilitator->color,
                    'image' => $trainingRecord->facilitator->image ?? null,
                ] : null,
                'account_manager' => $trainingRecord->account_manager ? [
                    'id' => $trainingRecord->account_manager->id,
                    'name' => $trainingRecord->account_manager->name,
                    'email' => $trainingRecord->account_manager->email ?? null,
                ] : null,
                'schedule' => $trainingRecord->schedule ? [
                    'from_date' => (string) $trainingRecord->schedule->from_date,
                    'to_date' => (string) $trainingRecord->schedule->to_date,
                    'from_time' => (string) $trainingRecord->schedule->from_time,
                    'to_time' => (string) $trainingRecord->schedule->to_time,
                ] : [],
            ];
        } else {
            $fixture = collect($this->fixtures('trainings.json'))->firstWhere('id', $id)
                ?: collect($this->fixtures('trainings.json'))->first();

            $training = $fixture ?: [];
        }

        $schedule = $training['schedule'] ?? [];
        $courseId = $trainingRecord ? $trainingRecord->course_id : ($training['course']['id'] ?? null);
        $companyId = $trainingRecord ? $trainingRecord->company_id : ($training['company']['id'] ?? null);
        $accountId = $trainingRecord ? $trainingRecord->account_id : ($training['account']['id'] ?? null);
        $facilitatorId = $trainingRecord ? $trainingRecord->facilitator_id : ($training['facilitator']['id'] ?? null);
        $needTransportationValue = $trainingRecord ? $trainingRecord->need_transportation : ($training['need_transportation'] ?? false);

        $normalizedTraining = [
            'id' => $training['id'] ?? $id,
            'mode' => $training['mode'] ?? 'virtual',
            'platform' => $training['platform'] ?? '',
            'conference_link' => $training['conference_link'] ?? '',
            'location' => $training['location'] ?? '',
            'assistant' => $training['assistant'] ?? '',
            'assistant_names' => $this->resolveAssistantNames($training['assistant'] ?? ''),
            'assistants' => $training['assistants'] ?? [],
            'course_id' => $courseId,
            'company_id' => $companyId,
            'account_manager_id' => $trainingRecord ? $trainingRecord->account_manager_id : ($training['account_manager']['id'] ?? null),
            'account_id' => $accountId,
            'facilitator_id' => $facilitatorId,
            'need_transportation' => filter_var($needTransportationValue, FILTER_VALIDATE_BOOLEAN) ? 'yes' : 'no',
            'outbound_pickup_time' => $training['outbound_pickup_time'] ?? '',
            'outbound_contact_number' => $training['outbound_contact_number'] ?? '',
            'outbound_pickup_location' => $training['outbound_pickup_location'] ?? '',
            'outbound_dropoff_location' => $training['outbound_dropoff_location'] ?? '',
            'return_trip_needed' => $training['return_trip_needed'] ?? false,
            'return_pickup_time' => $training['return_pickup_time'] ?? '',
            'return_contact_number' => $training['return_contact_number'] ?? '',
            'return_pickup_location' => $training['return_pickup_location'] ?? '',
            'return_dropoff_location' => $training['return_dropoff_location'] ?? '',
            'notify_coordinator' => $training['notify_coordinator'] ?? false,
            'coordinator_to_notify_list' => $training['coordinator_to_notify_list'] ?? ($training['coordinator_to_notify'] ?? null),
            'coordinator_to_notify' => $training['coordinator_to_notify'] ?? null,
            'from_date' => $schedule['from_date'] ?? now()->toDateString(),
            'to_date' => $schedule['to_date'] ?? now()->toDateString(),
            'from_time' => $schedule['from_time'] ?? '09:00:00',
            'to_time' => $schedule['to_time'] ?? '10:00:00',
            'start_date' => $schedule['from_date'] ?? now()->toDateString(),
            'end_date' => $schedule['to_date'] ?? now()->toDateString(),
            'course' => $training['course'] ?? null,
            'company' => $training['company'] ?? null,
            'account' => $training['account'] ?? null,
            'facilitator' => $training['facilitator'] ?? null,
            'account_manager' => $training['account_manager'] ?? null,
            'schedule' => [
                'from_date' => $schedule['from_date'] ?? now()->toDateString(),
                'to_date' => $schedule['to_date'] ?? now()->toDateString(),
                'from_time' => $schedule['from_time'] ?? '09:00:00',
                'to_time' => $schedule['to_time'] ?? '10:00:00',
            ],
        ];

        $normalizedTraining['coordinator_to_notify_users'] = $this->resolveCoordinatorUsers(
            $normalizedTraining['coordinator_to_notify_list'] ?? $normalizedTraining['coordinator_to_notify'] ?? ''
        );

        if (empty($normalizedTraining['coordinator_to_notify_list'])) {
            $normalizedTraining['coordinator_to_notify_list'] = implode(', ', $normalizedTraining['coordinator_to_notify_users']->pluck('id')->all());
        }

        $normalizedTraining['coordinator_names'] = $this->resolveCoordinatorNames(
            $normalizedTraining['coordinator_to_notify_list'] ?? $normalizedTraining['coordinator_to_notify'] ?? ''
        );

        $trainingObject = json_decode(json_encode($normalizedTraining), false);
        $trainingObject->coordinator_to_notify_users = $normalizedTraining['coordinator_to_notify_users'];

        // If we loaded a real DB training record, prefer live DB lists so the edit form
        // reflects current users, courses, companies and accounts. Otherwise fall back to fixtures.
        if ($trainingRecord) {
            $facilitatorsList = User::all();
            $coursesList = Course::all();
            $companiesList = Company::all();
            $accountsList = Account::all();
        } else {
            $facilitatorsList = $this->fixtureCollection('users.json');
            $coursesList = $this->fixtureCollection('courses.json');
            $companiesList = $this->fixtureCollection('companies.json');
            $accountsList = $this->fixtureCollection('accounts.json');
        }

        return view('add_training.edit_training', [
            'training' => $trainingObject,
            'facilitators' => $facilitatorsList,
            'courses' => $coursesList,
            'companies' => $companiesList,
            'accounts' => $accountsList,
        ]);
    }

    public function updateTraining(int $id): JsonResponse
    {
        // Diagnostic logging to help debug session/auth issues on save
        try {
            
            \Log::info('Demo updateTraining called', [
                'id' => $id,
                'auth_check' => Auth::check(),
                'auth_user_id' => Auth::id(),
                'session_id' => session()->getId(),
                'session_data_sample' => array_slice(session()->all(), 0, 10),
                'headers' => [
                    'host' => request()->header('host'),
                    'referer' => request()->header('referer'),
                    'user-agent' => request()->header('user-agent'),
                ],
                'method' => request()->method(),
                'content_type' => request()->header('content-type'),
                'input' => request()->all(),
            ]);
        } catch (\Exception $e) {
            // Logging must not break the response
            \Log::error('Failed to collect diagnostic info in updateTraining: ' . $e->getMessage());
        }

        // Delegate to the real TrainingController update method
        try {
            $trainingController = new TrainingController();
            $response = $trainingController->update(request(), $id);
            \Log::info('TrainingController::update() returned successfully');
            return $response;
        } catch (\Exception $e) {
            \Log::error('TrainingController::update() threw exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return error response instead of hanging
            return response()->json([
                'code' => '500',
                'message' => 'Error updating training: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteTraining(int $id): JsonResponse
    {
        try {
            $trainingController = new TrainingController();
            return $trainingController->destroy((string) $id);
        } catch (\Exception $e) {
            \Log::error('TrainingController::destroy() threw exception', [
                'id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting training: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function addUnavailabilityForm(): View
    {
        $user = Auth::user() ?? (object) [
            'id' => 1,
            'name' => 'Demo Admin',
            'usertype' => 'admin',
        ];

        return view('unavailability.add_unavailability', ['user' => $user]);
    }

    public function storeUnavailability(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 200,
            'data' => [
                'id' => 7001,
                'user_id' => (int) $request->input('user_id', 1),
                'reason' => (string) $request->input('reason', 'Demo unavailable'),
                'from_date' => (string) $request->input('from_date', now()->toDateString()),
                'to_date' => (string) $request->input('to_date', now()->toDateString()),
            ],
            'demo' => true,
        ], 200);
    }

    public function getUnavailabilities(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->fixtures('unavailabilities.json'),
            'message' => 'Unavailabilities retrieved successfully',
        ], 200);
    }

    public function checkUnavailability(Request $request, int $id): JsonResponse
    {
        $from = (string) $request->input('from_date', '');
        $to = (string) $request->input('to_date', '');

        $isUnavailable = collect($this->fixtures('unavailabilities.json'))->contains(function (array $entry) use ($id, $from, $to) {
            if ((int) ($entry['user_id'] ?? 0) !== $id) {
                return false;
            }

            $entryFrom = (string) ($entry['from_date'] ?? '');
            $entryTo = (string) ($entry['to_date'] ?? $entryFrom);

            return $from <= $entryTo && $to >= $entryFrom;
        });

        return response()->json([
            'success' => true,
            'user_id' => $id,
            'available' => !$isUnavailable,
        ]);
    }

    public function deleteUnavailability(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Unavailability deleted successfully (demo mode)',
            'id' => $id,
        ]);
    }

    public function manageAccess(): View
    {
        return view('access.manage_access', [
            'users' => $this->fixtureCollection('users.json'),
        ]);
    }

    public function addUserForm(): View
    {
        return view('access.add_user');
    }

    public function storeUser(): JsonResponse
    {
        return response()->json([
            'message' => 'User created (demo mode)',
            'demo' => true,
        ], 201);
    }

    public function getUser(int $id): JsonResponse
    {
        $user = $this->findUser($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    public function editUser(int $id): View
    {
        return view('access.edit_user', [
            'user' => $id,
        ]);
    }

    public function updateUser(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully (demo mode).',
            'redirect_url' => '/access',
            'id' => $id,
        ]);
    }

    public function changeCredentials(int $id)
    {
        $user = $this->findUser($id);

        if (!$user) {
            return redirect('/access');
        }

        return view('access.change_credentials', [
            'user' => (object) $user,
        ]);
    }

    public function updateCredentials(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Credentials updated successfully (demo mode).',
            'redirect_url' => '/access',
            'id' => $id,
        ]);
    }

    public function deleteUser(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully (demo mode).',
            'id' => $id,
        ]);
    }

    public function addCompany(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'company' => [
                'id' => 8801,
                'company_name' => (string) $request->input('company_name', 'Demo Company'),
                'contact_person' => (string) $request->input('contact_person', ''),
                'contact_number' => (string) $request->input('contact_number', ''),
                'email' => (string) $request->input('email', ''),
            ],
            'demo' => true,
        ], 200);
    }

    public function showCourses(): View
    {
        return view('configuration.courses', [
            'courses' => $this->fixtureCollection('courses.json'),
        ]);
    }

    public function showCourseDetails(int $id): JsonResponse
    {
        $course = collect($this->fixtures('courses.json'))->firstWhere('id', $id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json([
            'course_name' => $course['course_name'] ?? '',
            'course_code' => $course['course_code'] ?? '',
        ]);
    }

    public function addCourse(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'course' => [
                'id' => 9901,
                'course_name' => (string) $request->input('course_name', 'Demo Course'),
                'course_code' => (string) $request->input('course_code', 'DEMO-001'),
            ],
            'message' => 'Course added successfully!',
            'demo' => true,
        ]);
    }

    public function editCourse(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'course' => [
                'id' => $id,
                'course_name' => (string) $request->input('course_name', 'Demo Course'),
                'course_code' => (string) $request->input('course_code', 'DEMO-001'),
            ],
            'message' => 'Course updated successfully!',
            'demo' => true,
        ]);
    }

    public function deleteCourse(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully!',
            'id' => $id,
            'demo' => true,
        ]);
    }

    public function showCompanies(): View
    {
        return view('configuration.companies', [
            'company' => $this->fixtureCollection('companies.json'),
        ]);
    }

    public function showCompanyDetails(int $id): JsonResponse
    {
        $company = collect($this->fixtures('companies.json'))->firstWhere('id', $id);

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        return response()->json([
            'company_name' => $company['company_name'] ?? '',
            'contact_person' => $company['contact_person'] ?? '',
            'contact_number' => $company['contact_number'] ?? '',
            'email' => $company['email'] ?? '',
        ]);
    }

    public function editCompany(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'company' => [
                'id' => $id,
                'company_name' => (string) $request->input('company_name', 'Demo Company'),
                'contact_person' => (string) $request->input('contact_person', ''),
                'contact_number' => (string) $request->input('contact_number', ''),
                'email' => (string) $request->input('email', ''),
            ],
            'message' => 'Company updated successfully!',
            'demo' => true,
        ]);
    }

    public function deleteCompany(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully!',
            'id' => $id,
            'demo' => true,
        ]);
    }

    public function showAccounts(): View
    {
        return view('configuration.accounts', [
            'accounts' => $this->fixtureCollection('accounts.json'),
        ]);
    }

    public function showAccountDetails(int $id): JsonResponse
    {
        $account = collect($this->fixtures('accounts.json'))->firstWhere('id', $id);

        if (!$account) {
            return response()->json(['error' => 'account not found'], 404);
        }

        return response()->json([
            'account_email' => $account['account_email'] ?? '',
            'account_password' => $account['account_password'] ?? '',
        ]);
    }

    public function addAccount(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'account' => [
                'id' => 9701,
                'account_email' => (string) $request->input('account_email', 'demo-account@alps.local'),
                'account_password' => (string) $request->input('account_password', 'DemoPass#9'),
            ],
            'message' => 'Account added successfully!',
            'demo' => true,
        ]);
    }

    public function editAccount(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'account' => [
                'id' => $id,
                'account_email' => (string) $request->input('account_email', 'demo-account@alps.local'),
                'account_password' => (string) $request->input('account_password', 'DemoPass#9'),
            ],
            'message' => 'Account updated successfully!',
            'demo' => true,
        ]);
    }

    public function deleteAccount(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully!',
            'id' => $id,
            'demo' => true,
        ]);
    }
}

