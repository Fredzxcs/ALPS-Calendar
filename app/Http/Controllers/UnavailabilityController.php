<?php

namespace App\Http\Controllers;

use App\Models\Unavailability;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'reason' => 'required|string|max:255',
                'from_date' => 'required|date',
                'user_id' => 'required|integer', // Ensure user_id is sent
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ], [
                'reason.required' => 'The reason field is required.',
                'from_date.required' => 'The from date is required.',
                'from_date.date' => 'The from date must be a valid date.',
                'to_date.date' => 'The to date must be a valid date.',
                'to_date.after_or_equal' => 'The to date must be the same or later than the from date.',
                'user_id.required' => 'The user ID is required.',
            ]);

            // Create the unavailability record
            $unavailability = Unavailability::create($validatedData);

            // Return a JSON response for success
            return response()->json([
                'message' => 200,
                'data' => $unavailability,
            ], 200);
        } catch (ValidationException $e) {
            // Return a JSON response for validation errors
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return a JSON response for unexpected errors
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        // Validate incoming request (expects `from_date` and `to_date` in payload)
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

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
        try {
            $request->validate([
                'reason' => 'required|string|max:255',
                'from_date' => 'required|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ], [
                'reason.required' => 'The reason field is required.',
                'from_date.required' => 'The from date is required.',
                'from_date.date' => 'The from date must be a valid date.',
                'to_date.date' => 'The to date must be a valid date.',
                'to_date.after_or_equal' => 'The to date must be the same or later than the from date.',
            ]);

            $unavailability->update($request->all());

            return redirect()->route('unavailabilities.index')
                ->with('success', 'Unavailability record updated successfully.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred: ' . $e->getMessage())->withInput();
        }
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
