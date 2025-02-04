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
        $API_KEY = $this->get_api_key();

        $year = $request->input('year', date('Y'));

        $url = "https://calendarific.com/api/v2/holidays?api_key={$API_KEY}&country=PH&year={$year}";

        try {
            // Make GET request using Laravel's HTTP client
            $response = Http::get($url);

            // Check if the request was successful
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['status' => 200, 'error' => 'Failed to fetch holidays', 'details' => $response->json()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
