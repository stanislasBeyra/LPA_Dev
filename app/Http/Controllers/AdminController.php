<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    //
    public function addAdmin(Request $request)
    {
        try {
            // Validate input data
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'mobile' => 'required|string|max:20|unique:users,mobile',
                'username' => 'required|string|max:100|unique:users,username',
               'role' => 'required|integer|exists:roles,id', // 1 for admin
            ], [
                // Custom error messages
                'firstname.required' => 'First name is required.',
                'lastname.required' => 'Last name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'The email address must be valid.',
                'email.unique' => 'This email address is already in use.',
                'mobile.required' => 'Mobile number is required.',
                'mobile.unique' => 'This mobile number is already in use.',
                'username.required' => 'Username is required.',
                'username.unique' => 'This username is already taken.',
                'role.required' => 'Role is required.',
                'role.in' => 'The selected role is not valid.',
            ]);

            // Create a new admin user
             User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'username' => $validated['username'],
                'password' => Hash::make('12345678'), // Secure password hashing
                'role' => $validated['role'],
            ]);

            // Return with success message
            return back()->with('success', 'Admin has been created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error occurred while creating an admin: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the admin. Please try again.');
        }
    }


    public function updateAdmin(Request $request)
    {
        try {
            // Find the admin in the database
            $admin = User::findOrFail($request->AdminId);

            if(!$admin){
                return back()->with('error','Admin Not Found');
            }

            // Validate the input data
            $validated = $request->validate([
                'firstname' => 'sometimes|required|string|max:255',
                'lastname' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $admin->id,
                'mobile' => 'sometimes|required|string|max:20|unique:users,mobile,' . $admin->id,
                'username' => 'sometimes|required|string|max:100|unique:users,username,' . $admin->id,
                'role'=>'sometimes|'
            ], [
                // Custom error messages
                'firstname.required' => 'The first name is required.',
                'lastname.required' => 'The last name is required.',
                'email.required' => 'The email address is required.',
                'email.email' => 'The email address must be valid.',
                'email.unique' => 'This email address is already in use.',
                'mobile.required' => 'The mobile number is required.',
                'mobile.unique' => 'This mobile number is already in use.',
                'username.required' => 'The username is required.',
                'username.unique' => 'This username is already taken.',
                'password.min' => 'The password must be at least 8 characters.',
            ]);

            // Update the admin fields
            $admin->update([
                'firstname' => $validated['firstname'] ?? $admin->firstname,
                'lastname' => $validated['lastname'] ?? $admin->lastname,
                'email' => $validated['email'] ?? $admin->email,
                'mobile' => $validated['mobile'] ?? $admin->mobile,
                'username' => $validated['username'] ?? $admin->username,
                'role'=>$validated['role']??$admin->role
            ]);

            // Return with success message
            return back()->with('success', 'The admin\'s information has been successfully updated.');
        } catch (ModelNotFoundException $e) {
            // If the admin is not found
            return back()->with('error', 'The specified admin could not be found.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('An error occurred while updating the admin: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating. Please try again.');
        }
    }



    public function deleteAdmin(Request $request)
    {
        try {
            $admin = User::findOrFail($request->AdminId);
            if(!$admin){
                return back()->with('error','Admin Not Found');
            }
            $admin->delete();
            return back()->with('success', 'The admin has been successfully deleted.');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'The specified admin could not be found.');
        } catch (\Exception $e) {
            Log::error('Error occurred while deleting the admin: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the admin. Please try again.');
        }
    }
}
