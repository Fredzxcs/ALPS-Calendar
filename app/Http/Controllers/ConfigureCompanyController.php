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
}
