<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    //

    public function getUsersWithVendors($id)
    {
        // Retrieve the user with the given $id and their associated vendor and role, filtering by role 3
        $users = User::where('id', $id)
            ->where('role', 3)  // Ensure we're fetching users with the role of 3
            ->with(['vendor', 'role'])  // Eager load vendor and role relationships
            ->orderBy('id', 'desc')  // Order by user id in descending order
            ->get();

        //   dd($users);
        // return $users;
        // Return the view with the users data
        return view('adminComponent.vendors-detail', compact('users'));
    }



    public function VendorinfoUpdate(Request $request)
    {
        // Retrieve the User by ID
        $user = User::findOrFail($request->user_id);

        // Validate the input data
        $validated = $request->validate([
            // User table section
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'role' => 'required|integer',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'mobile' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'mobile')->ignore($user->id),
            ],

            // Vendor table section
            'vendorname' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vendors', 'vendorname')->ignore(optional($vendor ?? null)->id),
            ],
            'contactpersonname' => 'required|string|max:255',
            'businessregno' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vendors', 'businessregno')->ignore(optional($vendor ?? null)->id),
            ],
            'taxidnumber' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vendors', 'taxidnumber')->ignore(optional($vendor ?? null)->id),
            ],
            'businesscategory' => 'required|string|max:255',
            'businessaddress' => 'required|string',
            'businessemail' => [
                'nullable',
                'email',
                Rule::unique('vendors', 'businessemail')->ignore(optional($vendor ?? null)->id),
            ],
            'bank_name_1' => 'nullable|string|max:255',
            'bankaccount1' => 'nullable|string|max:255',
            'bankname2' => 'nullable|string|max:255',
            'bankaccount2' => 'nullable|string|max:255',
            'accountholdername' => 'nullable|string|max:255',
        ], [
            // Custom messages for the user section
            'firstname.required' => 'The first name is required.',
            'lastname.required' => 'The last name is required.',
            'role.required' => 'The role is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be valid.',
            'email.unique' => 'This email address is already in use.',
            'mobile.required' => 'The mobile number is required.',
            'mobile.max' => 'The mobile number must not exceed 15 characters.',
            'mobile.unique' => 'This mobile number is already in use.',

            // Custom messages for the vendor section
            'vendorname.required' => 'The vendor name is required.',
            'contactpersonname.required' => 'The contact person name is required.',
            'businessregno.required' => 'The business registration number is required.',
            'taxidnumber.required' => 'The tax identification number is required.',
            'businesscategory.required' => 'The business category is required.',
            'businessaddress.required' => 'The business address is required.',
            'businessemail.email' => 'The business email must be valid.',
            'businessemail.unique' => 'This business email is already in use.',
        ]);

        // Update user data
        $user->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
        ]);

        // Find Vendor by user_id or create a new one
        $vendor = Vendor::where('user_id', $user->id)->first();

        if ($vendor) {
            // Update the existing vendor
            $vendor->update([
                'vendorname' => $validated['vendorname'],
                'contactpersonname' => $validated['contactpersonname'],
                'businessregno' => $validated['businessregno'],
                'taxidnumber' => $validated['taxidnumber'],
                'businesscategory' => $validated['businesscategory'],
                'businessaddress' => $validated['businessaddress'],
                'businessemail' => $validated['businessemail'],
                'bank_name_1' => $validated['bank_name_1'],
                'bankaccount1' => $validated['bankaccount1'],
                'bankname2' => $validated['bankname2'],
                'bankaccount2' => $validated['bankaccount2'],
                'accountholdername' => $validated['accountholdername'],
            ]);
            return back()->with('success', 'Vendor information updated successfully.');
        } else {
            // Create a new vendor
            Vendor::create([
                'user_id' => $user->id,
                'vendorname' => $validated['vendorname'],
                'contactpersonname' => $validated['contactpersonname'],
                'businessregno' => $validated['businessregno'],
                'taxidnumber' => $validated['taxidnumber'],
                'businesscategory' => $validated['businesscategory'],
                'businessaddress' => $validated['businessaddress'],
                'businessemail' => $validated['businessemail'],
                'bank_name_1' => $validated['bank_name_1'],
                'bankaccount1' => $validated['bankaccount1'],
                'bankname2' => $validated['bankname2'],
                'bankaccount2' => $validated['bankaccount2'],
                'accountholdername' => $validated['accountholdername'],
            ]);
            return back()->with('success', 'Vendor updated successfully.');
        }
    }

    public function resetVendorPassword(Request $request)
{
    try {
        // Checks if the user ID is provided and the user exists
        $user = User::find($request->userid);
        
        if (!$user) {
            return back()->with('error', 'User not found');
        }
        
        // Resets the user's password
        $user->password = Hash::make('1234567890');
        
        // Saves the user after password reset
        $user->save();
        
        // Returns a success message
        return back()->with('success', 'Password reset successfully');
        
    } catch (\Exception $e) {
        // Returns an error message in case of an exception
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}


    public function deleteVendors(Request $request)
    {
        try {
            $user = User::find($request->userid);
            if (!$user) {
                return back()->with('error', 'User not found');
            }

            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!$vendor) {
                return back()->with('error', 'Vendor not found');
            }

            $user->delete();
            $vendor->delete();

            return back()->with('success', 'Vendor successfully deleted');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred');
        }
    }
}
