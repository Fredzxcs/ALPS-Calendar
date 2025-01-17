<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class ConfigureAccountController extends Controller
{
    public function showAccount()
    {
        // Retrieve all accounts from the database
        $accounts = Account::all();

        // Pass accounts to the view
        return view('configuration.accounts', compact('accounts'));
    }

    public function showAccountDetails($id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['error' => 'account not found'], 404);
        }

        return response()->json([
            'account_email' => $account->account_email,
            'account_password' => $account->account_password
        ]);
    }

    public function addAccount(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'account_email' => 'required|unique:credentials|max:255',  // Fixed the validation rule
            'account_password' => 'required|max:255', 
        ]);
    
        // Create and store the new account
        $account = new Account();  // Corrected the model instantiation
        $account->account_email = $validated['account_email'];  // Use 'account_email' and 'account_password'
        $account->account_password = $validated['account_password'];  // Hash the password
        $account->save();
    
        // Respond with a success message
        return response()->json([
            'success' => true,
            'message' => 'Account added successfully!',
        ]);
    }
    

    public function editAccount(Request $request, $id)
    {
        // Validate the incoming data
        $rules = [
            'account_email' => "required|unique:credentials,account_email,{$id}|max:255",
            'account_password' => 'required|max:255', 
        ];


        $validated = $request->validate($rules);

        // Find the company by ID
        $account = Account::findOrFail($id);

        // Update the company's details
        $account->fill($validated);

        $account->save();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully!',
        ]);
    }

    public function deleteAccount($id)
    {
        $account = Account::findOrFail($id);

        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully!',
        ]);
    }
}
