<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        return response()->json([
            'message' => '200',
            'training' => [
                'id' => 9001,
                'course_id' => (int) $request->input('course_id', 1),
                'mode' => (string) $request->input('mode', 'virtual'),
                'facilitator_id' => $request->input('facilitator_id'),
                'company_id' => $request->input('company_id'),
                'account_id' => $request->input('account_id'),
            ],
            'schedule' => [
                'from_date' => (string) $request->input('from_date', now()->toDateString()),
                'to_date' => (string) $request->input('to_date', now()->toDateString()),
                'from_time' => (string) $request->input('from_time', '09:00:00'),
                'to_time' => (string) $request->input('to_time', '11:00:00'),
            ],
            'demo' => true,
        ], 200);
    }

    public function getTraining(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->fixtures('trainings.json'),
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
