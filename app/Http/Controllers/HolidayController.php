<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HolidayController extends Controller
{
    private function get_api_key()
    {
        $API_KEY = 'CStMRmEfqI2qTl3m7oo8AQi98C8zsxx7';
        return $API_KEY;
    }

    public function get_holidays(Request $request)
    {
        // Prefer the deployable public JSON file first, then fall back to the source copy.
        $candidatePaths = [
            public_path('data/philippines_holidays.json'),
            resource_path('data/philippines_holidays.json'),
        ];

        foreach ($candidatePaths as $localPath) {
            if (!file_exists($localPath)) {
                continue;
            }

            $content = file_get_contents($localPath);
            $data = json_decode($content, true);
            if (isset($data['holidays']) && is_array($data['holidays'])) {
                return response()->json(['response' => ['holidays' => $data['holidays']]]);
            }
        }

        // Fallback to Calendarific API if local file not available
        $API_KEY = $this->get_api_key();
        $year = $request->input('year', date('Y'));
        $url = "https://calendarific.com/api/v2/holidays?api_key={$API_KEY}&country=PH&year={$year}";

        try {
            $response = Http::get($url);
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['status' => 200, 'error' => 'Failed to fetch holidays', 'details' => $response->json()], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
