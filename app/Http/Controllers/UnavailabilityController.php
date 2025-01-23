<?php

namespace App\Http\Controllers;

use App\Models\Unavailability;
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

            return redirect()->route('unavailabilities.index')
                ->with('success', 'Unavailability record deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the record: ' . $e->getMessage());
        }
    }
}
