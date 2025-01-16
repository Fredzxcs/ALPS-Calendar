<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class ConfigureCompanyController extends Controller
{
    public function showCompany()
    {
        // Retriev list of companies
        $company = Company::all();

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
        $validated = $request->validate([
            'company_name' => 'required|max:255', 
            'contact_person'=> 'required|max:255', 
            'contact_number'=> 'required|unique:company|max:255',
            'email' => 'required|unique:company|max:255',
        ]);

        $company = new Company();
        $company->company_name = $validated['company_name'];
        $company->contact_person = $validated['contact_person'];
        $company->contact_number = $validated['contact_number'];
        $company->email = $validated['email'];
        $company->save();

        return response()->json([
            'success' => true,
            'message' => 'Company added successfully!'
        ]);
    }

    public function editCompany(Request $request, $id)
    {
        // Define validation rules
        $rules = [
            'company_name'   => 'sometimes|required|max:255', // Optional, but required if present
            'contact_person' => 'sometimes|required|max:255', 
            'contact_number' => "sometimes|required|unique:company,contact_number,{$id}|max:255", 
            'email'          => "sometimes|required|unique:company,email,{$id}|max:255", 
        ];
    
        // Validate incoming data
        $validated = $request->validate($rules);
    
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
