<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

        $vendor = Vendor::where('user_id', $user->id)->first();

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
                Rule::unique('vendors', 'vendorname')->ignore($vendor->id??null),
            ],
            'contactpersonname' => 'required|string|max:255',
            'businessregno' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vendors', 'businessregno')->ignore($vendor->id??null),
            ],
            'taxidnumber' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vendors', 'taxidnumber')->ignore($vendor->id??null),
            ],
            'businesscategory' => 'required|string|max:255',
            'businessaddress' => 'required|string',
            'businessemail' => [
                'nullable',
                'email',
                Rule::unique('vendors', 'businessemail')->ignore($vendor->id??null),
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
        // $vendor = Vendor::where('user_id', $user->id)->first();

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


//     public function VendorinfoUpdate(Request $request)
// {
//     try {
//         // Retrieve the User by ID
//         $user = User::findOrFail($request->user_id);

//         // Validate the input data
//         $validated = $request->validate([
//             // User table section
//             'firstname' => 'required|string|max:255',
//             'lastname' => 'required|string|max:255',
//             'role' => 'required|integer',
//             'email' => 'nullable|email|max:255|unique:users,email,' . $request->user_id,
//             'mobile' => 'nullable|string|max:20|unique:users,mobile,' . $request->user_id,
//             'vendorname' => 'nullable|string|max:255|unique:vendors,vendorname,' . $request->user_id,
//             'contactpersonname' => 'required|string|max:255',
//             'businessregno' => 'nullable|string|max:255|unique:vendors,businessregno,' . $request->user_id,
//             'taxidnumber' => 'nullable|string|max:255|unique:vendors,taxidnumber,' . $request->user_id,

//             'businesscategory' => 'required|string|max:255',
//             'businessaddress' => 'required|string',
//             'businessemail' => 'nullable|string|max:20|unique:vendors,businessemail,' . $request->user_id,

//             'bank_name_1' => 'nullable|string|max:255',
//             'bankaccount1' => 'nullable|string|max:255',
//             'bankname2' => 'nullable|string|max:255',
//             'bankaccount2' => 'nullable|string|max:255',
//             'accountholdername' => 'nullable|string|max:255',
//         ], [
//             // Custom messages for the user section
//             'firstname.required' => 'The first name is required.',
//             'lastname.required' => 'The last name is required.',
//             'role.required' => 'The role is required.',
//             'email.required' => 'The email address is required.',
//             'email.email' => 'The email address must be valid.',
//             'email.unique' => 'This email address is already in use.',
//             'mobile.required' => 'The mobile number is required.',
//             'mobile.max' => 'The mobile number must not exceed 15 characters.',
//             'mobile.unique' => 'This mobile number is already in use.',

//             // Custom messages for the vendor section
//             'vendorname.required' => 'The vendor name is required.',
//             'contactpersonname.required' => 'The contact person name is required.',
//             'businessregno.required' => 'The business registration number is required.',
//             'taxidnumber.required' => 'The tax identification number is required.',
//             'businesscategory.required' => 'The business category is required.',
//             'businessaddress.required' => 'The business address is required.',
//             'businessemail.email' => 'The business email must be valid.',
//             'businessemail.unique' => 'This business email is already in use.',
//         ]);

//         // Update user data
//         $user->update([
//             'firstname' => $validated['firstname'],
//             'lastname' => $validated['lastname'],
//             'role' => $validated['role'],
//             'email' => $validated['email'],
//             'mobile' => $validated['mobile'],
//         ]);

//         // Find Vendor by user_id or create a new one
//         $vendor = Vendor::where('user_id', $user->id)->first();

//         if ($vendor) {
//             // Update the existing vendor
//             $vendor->update([
//                 'vendorname' => $validated['vendorname'],
//                 'contactpersonname' => $validated['contactpersonname'],
//                 'businessregno' => $validated['businessregno'],
//                 'taxidnumber' => $validated['taxidnumber'],
//                 'businesscategory' => $validated['businesscategory'],
//                 'businessaddress' => $validated['businessaddress'],
//                 'businessemail' => $validated['businessemail'],
//                 'bank_name_1' => $validated['bank_name_1'],
//                 'bankaccount1' => $validated['bankaccount1'],
//                 'bankname2' => $validated['bankname2'],
//                 'bankaccount2' => $validated['bankaccount2'],
//                 'accountholdername' => $validated['accountholdername'],
//             ]);
//             return back()->with('success', 'Vendor information updated successfully.');
//         } else {
//             // Create a new vendor
//             Vendor::create([
//                 'user_id' => $user->id,
//                 'vendorname' => $validated['vendorname'],
//                 'contactpersonname' => $validated['contactpersonname'],
//                 'businessregno' => $validated['businessregno'],
//                 'taxidnumber' => $validated['taxidnumber'],
//                 'businesscategory' => $validated['businesscategory'],
//                 'businessaddress' => $validated['businessaddress'],
//                 'businessemail' => $validated['businessemail'],
//                 'bank_name_1' => $validated['bank_name_1'],
//                 'bankaccount1' => $validated['bankaccount1'],
//                 'bankname2' => $validated['bankname2'],
//                 'bankaccount2' => $validated['bankaccount2'],
//                 'accountholdername' => $validated['accountholdername'],
//             ]);
//             return back()->with('success', 'Vendor created successfully.');
//         }
//     } catch (\Throwable $e) {
//         // Log the error and return a failure message
//         Log::error('Error updating vendor info: ' . $e->getMessage());
//         return back()->with('error', 'An error occurred while updating the vendor information. Please try again later.' . $e->getMessage());
//     }
// }





    public function UpdateVendorPassword(Request $request)
    {
        try {
            // Validate user inputs
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ], [
                'current_password.required' => 'The current password field is required.',
                'new_password.required' => 'The new password field is required.',
                'new_password.min' => 'The new password must be at least 8 characters long.',
                'new_password.confirmed' => 'The new password confirmation does not match.',
            ]);


            $user = Auth::user();



            // Check if the current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'The current password is incorrect.');
            }

            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Your password has been updated successfully.');
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating your password.' . $e->getMessage());
        }
    }

    public function updatevendorlogo(Request $request)
    {
        try {
            $authuser = Auth::user();
            if (!$authuser) {
                return back()->with('error', 'Accès non autorisé.');
            }

            $user = User::find($authuser->id);

            // Valider le fichier
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Chemin vers le dossier des avatars
            $avatarDirectory = public_path('app/avatars');

            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && file_exists($avatarDirectory . '/' . $user->avatar)) {
                $de =   unlink($avatarDirectory . '/' . $user->avatar);
            }


            // Générer un nom unique pour le nouvel avatar
            $avatarName = time() . '.' . $request->file('avatar')->extension();

            // Déplacer le nouveau fichier dans le dossier
            $request->file('avatar')->move($avatarDirectory, $avatarName);

            $user->update(['avatar' => 'avatars/' . $avatarName]);

            // Mettre à jour le chemin de l'avatar dans la base de données
            // $user->avatar='avatars/' . $avatarName;
            // $user->s

            return back()->with('success', 'Votre avatar a été mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'avatar : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour de votre avatar.');
        }
    }

    public function AdminChangeVendorAvatar(Request $request)
    {
        // Valider la requête
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        // Récupérer l'ID du vendeur depuis la requête
        $vendorId = $request->input('vendor_id');

        // Récupérer le vendeur
        $vendor = User::findOrFail($vendorId);
        if (!$vendor) {
            return back()->with('error', 'vendor Not found');
        }

        // Créer le dossier avatars s'il n'existe pas
        $avatarDirectory = public_path('app/avatars');
        if (!file_exists($avatarDirectory)) {
            mkdir($avatarDirectory, 0777, true);
        }

        // Générer un nom unique pour l'avatar
        $avatarName = time() . '.' . $request->file('avatar')->extension();

        // Supprimer l'ancien avatar s'il existe
        if ($vendor->avatar) {
            $oldAvatarPath = public_path('app/' . $vendor->avatar);
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        try {
            // Déplacer le nouveau fichier dans le dossier
            $request->file('avatar')->move($avatarDirectory, $avatarName);

            // Mettre à jour l'avatar dans la base de données
            $vendor->update(['avatar' => 'avatars/' . $avatarName]);

            return back()->with('success', 'Avatar updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update avatar: ' . $e->getMessage());
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
