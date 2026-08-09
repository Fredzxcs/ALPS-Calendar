<?php

namespace App\Http\Controllers;

use App\Models\Unavailability;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnavailabilityController extends Controller //TODO: Update return views
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unavailabilities = Unavailability::all();
        return view('view name here', compact('unavailabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the currently authenticated user
        $user = auth()->user();

        // Pass the user info to the view
        return view('unavailability.add_unavailability', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => ['required', 'string', 'max:255'],
            'from_date' => ['required', 'date'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ], [
            'reason.required' => 'Please tell us why you will be unavailable.',
            'reason.string' => 'The reason should be written as text.',
            'reason.max' => 'The reason is too long. Please keep it to 255 characters or fewer.',
            'from_date.required' => 'Please choose the first day of your unavailability.',
            'from_date.date' => 'The start date needs to be a valid date.',
            'user_id.required' => 'We could not identify the user for this request.',
            'user_id.integer' => 'The user selection is not valid.',
            'user_id.exists' => 'The selected user could not be found. Please refresh and try again.',
            'to_date.date' => 'The end date needs to be a valid date.',
            'to_date.after_or_equal' => 'The end date must be the same as or later than the start date.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the unavailability details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $unavailability = Unavailability::create($validator->validated());

        return response()->json([
            'message' => 200,
            'data' => $unavailability,
        ], 200);
    }


    public function getUnavailabilities(Request $request)
    {
        // Fetch unavailabilities with user relationships
        $unavailabilities = Unavailability::with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $unavailabilities,
            'message' => $unavailabilities->isEmpty() ? 'No unavailabilities found' : 'Unavailabilities retrieved successfully'
        ], 200);
    }


    public function checkUnavailability(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
        ], [
            'from_date.required' => 'Please choose a start date before checking availability.',
            'from_date.date' => 'The start date must be a valid date.',
            'to_date.required' => 'Please choose an end date before checking availability.',
            'to_date.date' => 'The end date must be a valid date.',
            'to_date.after_or_equal' => 'The end date must be the same as or later than the start date.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the date range and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Extract dates from request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Check for overlapping unavailability records
        $isUnavailable = Unavailability::where('user_id', $id)
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('from_date', [$fromDate, $toDate])
                      ->orWhereBetween('to_date', [$fromDate, $toDate])
                      ->orWhere(function ($query) use ($fromDate, $toDate) {
                          $query->where('from_date', '<=', $fromDate)
                                ->where('to_date', '>=', $toDate);
                      });
            })
            ->exists(); // Returns `true` if an overlapping record exists

        return response()->json([
            'success' => true,
            'user_id' => $id,
            'available' => !$isUnavailable // Return `true` if user is available
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Unavailability $unavailability)
    {
        return view('unavailabilities.show', compact('unavailability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unavailability $unavailability)
    {
        return view('unavailabilities.edit', compact('unavailability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unavailability $unavailability)
    {
        $validator = Validator::make($request->all(), [
            'reason' => ['required', 'string', 'max:255'],
            'from_date' => ['required', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ], [
            'reason.required' => 'Please tell us why this time should stay blocked out.',
            'reason.string' => 'The reason should be written as text.',
            'reason.max' => 'The reason is too long. Please keep it to 255 characters or fewer.',
            'from_date.required' => 'Please choose the first day of the blocked period.',
            'from_date.date' => 'The start date needs to be a valid date.',
            'to_date.date' => 'The end date needs to be a valid date.',
            'to_date.after_or_equal' => 'The end date must be the same as or later than the start date.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $unavailability->update($validator->validated());

        return redirect()->route('unavailabilities.index')
            ->with('success', 'Unavailability record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unavailability $unavailability)
    {
        try {
            $unavailability->delete();

            // Check if the request is AJAX and return JSON
            if (request()->ajax()) {
                return response()->json(['success' => 'Unavailability record deleted successfully.']);
            }

            // For non-AJAX requests, redirect as usual
            return redirect()->route('unavailabilities.index')
                ->with('success', 'Unavailability record deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'An error occurred while deleting the record: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'An error occurred while deleting the record: ' . $e->getMessage());
        }
    }

}
