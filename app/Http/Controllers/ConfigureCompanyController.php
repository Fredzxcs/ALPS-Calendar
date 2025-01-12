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
}
