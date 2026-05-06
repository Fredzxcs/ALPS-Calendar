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
            $companyId = $this->syncCompanyFromFixture($request->integer('company_id'));
            $accountId = $this->syncAccountFromFixture($request->integer('account_id'));

            if (!$courseId) {
                $courseId = $this->resolveCourseId($request->integer('course_id'));
            }
            if (!$facilitatorId) {
                $facilitatorId = $this->resolveNullableUserId($request->integer('facilitator_id'));
            }
            if (!$companyId) {
                $companyId = $this->resolveNullableCompanyId($request->integer('company_id'));
            }
            if (!$accountId) {
                $accountId = $this->resolveNullableAccountId($request->integer('account_id'));
            }

            // Create the Training record
            $training = Training::create([
                'course_id' => $courseId,
                'mode' => $request->mode,
                'facilitator_id' => $facilitatorId,
                'location' => $request->location,
                'platform' => $request->platform,
                'company_id' => $companyId,
                'assistant' => $request->assistant,
                'account_id' => $accountId,
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

                    // Fetch the full training with relationships
                    $trainingInfo = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
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
        // Fetch trainings from database
        $trainings = Training::with(['schedule', 'facilitator', 'course', 'company', 'account'])
            ->get()
            ->map(function ($training) {
                return [
                    'id' => $training->id,
                    'course_id' => $training->course_id,
                    'mode' => $training->mode,
                    'platform' => $training->platform,
                    'location' => $training->location,
                    'assistant' => $training->assistant,
                    'facilitator_id' => $training->facilitator_id,
                    'company_id' => $training->company_id,
                    'account_id' => $training->account_id,
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
        $training = collect($this->fixtures('trainings.json'))->firstWhere('id', $id);
        $training = $training ?: collect($this->fixtures('trainings.json'))->first();

        $schedule = $training['schedule'] ?? [];

        $normalizedTraining = [
            'id' => $training['id'] ?? $id,
            'mode' => $training['mode'] ?? 'virtual',
            'platform' => $training['platform'] ?? '',
            'location' => $training['location'] ?? '',
            'assistant' => $training['assistant'] ?? '',
            'start_date' => $schedule['from_date'] ?? now()->toDateString(),
            'end_date' => $schedule['to_date'] ?? now()->toDateString(),
            'course' => $training['course'] ?? null,
            'company' => $training['company'] ?? null,
            'account' => $training['account'] ?? null,
            'facilitator' => $training['facilitator'] ?? null,
            'schedule' => [
                'from_date' => $schedule['from_date'] ?? now()->toDateString(),
                'to_date' => $schedule['to_date'] ?? now()->toDateString(),
                'from_time' => $schedule['from_time'] ?? '09:00:00',
                'to_time' => $schedule['to_time'] ?? '10:00:00',
            ],
        ];

        return view('add_training.edit_training', [
            'training' => json_decode(json_encode($normalizedTraining), false),
            'facilitators' => $this->fixtureCollection('users.json'),
            'courses' => $this->fixtureCollection('courses.json'),
            'companies' => $this->fixtureCollection('companies.json'),
            'accounts' => $this->fixtureCollection('accounts.json'),
        ]);
    }

    public function updateTraining(int $id): JsonResponse
    {
        return response()->json([
            'code' => '200',
            'message' => 'Training session updated successfully (demo mode)',
            'trainingSession' => ['id' => $id],
            'schedule' => ['training_id' => $id],
            'demo' => true,
        ], 200);
    }

    public function deleteTraining(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Training deleted successfully (demo mode)',
            'id' => $id,
        ]);
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
