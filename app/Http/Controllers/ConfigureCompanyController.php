<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConfigureCompanyController extends Controller
{
    public function showCompany()
    {
        // Retriev list of companies
        $company = Company::orderBy('company_name', 'asc')->get();

        // Pass data to view (company.blade file)
        return view('configuration.companies', compact('company'));
    }

    // In ConfigureCompanyController.php

    public function showCompanyDetails($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        return response()->json([
            'company_name' => $company->company_name,
            'contact_person' => $company->contact_person,
            'contact_number' => $company->contact_number,
            'email' => $company->email
        ]);
    }

    public function addCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255', 'unique:company,company_name'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255', 'unique:company,contact_number'],
            'email' => ['nullable', 'email', 'max:255', 'unique:company,email'],
        ], [
            'company_name.required' => 'Please enter the company name before saving.',
            'company_name.string' => 'The company name should be written as text.',
            'company_name.max' => 'The company name is too long. Please keep it to 255 characters or fewer.',
            'company_name.unique' => 'That company name already exists. Please enter a different one.',
            'contact_person.string' => 'The contact person should be written as text.',
            'contact_person.max' => 'The contact person name is too long. Please keep it to 255 characters or fewer.',
            'contact_number.string' => 'The contact number should be written as text.',
            'contact_number.max' => 'The contact number is too long. Please keep it to 255 characters or fewer.',
            'contact_number.unique' => 'That contact number is already in use. Please choose another one.',
            'email.email' => 'Please enter a valid company email address.',
            'email.max' => 'The email address is too long. Please keep it to 255 characters or fewer.',
            'email.unique' => 'That company email is already in use. Please choose another one.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the company details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $company = new Company();
        $company->company_name = $validated['company_name'];
        $company->contact_person = $validated['contact_person'];
        $company->contact_number = $validated['contact_number'];
        $company->email = $validated['email'];
        $company->save();

        return response()->json([
            'success' => true,
            'company' => $company,
            'message' => 'Company added successfully!'
        ]);
    }

    public function editCompany(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255', Rule::unique('company', 'company_name')->ignore($id)],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255', Rule::unique('company', 'contact_number')->ignore($id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('company', 'email')->ignore($id)],
        ], [
            'company_name.required' => 'Please enter the company name before updating.',
            'company_name.string' => 'The company name should be written as text.',
            'company_name.max' => 'The company name is too long. Please keep it to 255 characters or fewer.',
            'company_name.unique' => 'That company name already exists. Please enter a different one.',
            'contact_person.string' => 'The contact person should be written as text.',
            'contact_person.max' => 'The contact person name is too long. Please keep it to 255 characters or fewer.',
            'contact_number.string' => 'The contact number should be written as text.',
            'contact_number.max' => 'The contact number is too long. Please keep it to 255 characters or fewer.',
            'contact_number.unique' => 'That contact number is already in use. Please choose another one.',
            'email.email' => 'Please enter a valid company email address.',
            'email.max' => 'The email address is too long. Please keep it to 255 characters or fewer.',
            'email.unique' => 'That company email is already in use. Please choose another one.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the company details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Find the company by ID
        $company = Company::findOrFail($id);

        // Update only the fields provided in the request
        $company->fill($validated);

        // Save the updated company
        $company->save();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully!',
        ]);
    }

    public function deleteCompany($id)
    {
        // Find the company by ID
        $company = Company::findOrFail($id);

        // Delete the company
        $company->delete();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully!',
        ]);
    }

}
