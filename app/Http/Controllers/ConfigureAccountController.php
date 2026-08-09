<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConfigureAccountController extends Controller
{
    public function showAccount()
    {
        // Retrieve all accounts from the database
        $accounts = Account::orderBy('account_email', 'asc')->get();

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
        $validator = Validator::make($request->all(), [
            'account_email' => ['required', 'email', 'max:255', 'unique:credentials,account_email'],
            'account_password' => ['required', 'string', 'max:255'],
        ], [
            'account_email.required' => 'Please enter the account email address before saving.',
            'account_email.email' => 'Please enter a valid email address such as name@example.com.',
            'account_email.max' => 'The account email is too long. Please keep it to 255 characters or fewer.',
            'account_email.unique' => 'That account email is already in use. Please choose another one.',
            'account_password.required' => 'Please enter the account password before saving.',
            'account_password.string' => 'The account password should be written as text.',
            'account_password.max' => 'The account password is too long. Please keep it to 255 characters or fewer.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the account details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

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
        $validator = Validator::make($request->all(), [
            'account_email' => ['required', 'email', 'max:255', Rule::unique('credentials', 'account_email')->ignore($id)],
            'account_password' => ['required', 'string', 'max:255'],
        ], [
            'account_email.required' => 'Please enter the account email address before updating.',
            'account_email.email' => 'Please enter a valid email address such as name@example.com.',
            'account_email.max' => 'The account email is too long. Please keep it to 255 characters or fewer.',
            'account_email.unique' => 'That account email is already in use. Please choose another one.',
            'account_password.required' => 'Please enter the account password before updating.',
            'account_password.string' => 'The account password should be written as text.',
            'account_password.max' => 'The account password is too long. Please keep it to 255 characters or fewer.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please review the account details and try again.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

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
