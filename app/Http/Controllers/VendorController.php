<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\vendor;
use Illuminate\Http\Request;
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

    //     public function VendorinfoUpdate(Request $request)
    // {
    //     try {
    //         // Récupération du fournisseur et de l'utilisateur

    //         $user = User::findOrFail($request->user_id);
    //         if(!$user){
    //             return back()->with('error','Vendor not found');
    //         }
    //         $vendor = Vendor::where('user_id',$user->id)->first();
    //         if(!$vendor){
    //             return back()->with('error','Vendor not found');
    //         }

    //         // Validation des données
    //         $validated = $request->validate([
    //             // Section de la table user
    //             'firstname' => 'required|string|max:255',
    //             'lastname' => 'required|string|max:255',
    //             'role' => 'required|integer',
    //             'email' => [
    //                 'required',
    //                 'email',
    //                 Rule::unique('users', 'email')->ignore($user->id),
    //             ],
    //             'mobile' => [
    //                 'required',
    //                 'string',
    //                 'max:15',
    //                 Rule::unique('users', 'mobile')->ignore($user->id),
    //             ],

    //             // Section de la table vendor
    //             'vendorname' => [
    //                 'required',
    //                 'string',
    //                 'max:255',
    //                 Rule::unique('vendors', 'vendorname')->ignore($vendor->id),
    //             ],
    //             'contactpersonname' => 'required|string|max:255',
    //             'businessregno' => [
    //                 'required',
    //                 'string',
    //                 'max:255',
    //                 Rule::unique('vendors', 'businessregno')->ignore($vendor->id),
    //             ],
    //             'taxidnumber' => [
    //                 'required',
    //                 'string',
    //                 'max:255',
    //                 Rule::unique('vendors', 'taxidnumber')->ignore($vendor->id),
    //             ],
    //             'businesscategory' => 'required|string|max:255',
    //             'businessaddress' => 'required|string',
    //             'businessemail' => [
    //                 'nullable',
    //                 'email',
    //                 Rule::unique('vendors', 'businessemail')->ignore($vendor->id),
    //             ],
    //             'bank_name_1' => 'nullable|string|max:255',
    //             'bankaccount1' => 'nullable|string|max:255',
    //             'bankname2' => 'nullable|string|max:255',
    //             'bankaccount2' => 'nullable|string|max:255',
    //             'accountholdername' => 'nullable|string|max:255',
    //         ], [
    //             // Messages personnalisés pour la section user
    //             'firstname.required' => 'Le prénom est requis.',
    //             'lastname.required' => 'Le nom est requis.',
    //             'role.required' => 'Le rôle est requis.',
    //             'email.required' => 'L\'adresse email est requise.',
    //             'email.email' => 'L\'adresse email doit être valide.',
    //             'email.unique' => 'Cette adresse email est déjà utilisée.',
    //             'mobile.required' => 'Le numéro de téléphone est requis.',
    //             'mobile.max' => 'Le numéro de téléphone ne doit pas dépasser 15 caractères.',
    //             'mobile.unique' => 'Ce numéro de téléphone est déjà utilisé.',

    //             // Messages personnalisés pour la section vendor
    //             'vendorname.required' => 'Le nom du fournisseur est requis.',
    //             'contactpersonname.required' => 'Le nom de la personne de contact est requis.',
    //             'businessregno.required' => 'Le numéro d\'enregistrement de l\'entreprise est requis.',
    //             'taxidnumber.required' => 'Le numéro d\'identification fiscale est requis.',
    //             'businesscategory.required' => 'La catégorie de l\'entreprise est requise.',
    //             'businessaddress.required' => 'L\'adresse de l\'entreprise est requise.',
    //             'businessemail.email' => 'L\'adresse email de l\'entreprise doit être valide.',
    //             'businessemail.unique' => 'Cette adresse email d\'entreprise est déjà utilisée.',
    //         ]);

    //         // Mise à jour de l'utilisateur
    //         $user->update([
    //             'firstname' => $validated['firstname'],
    //             'lastname' => $validated['lastname'],
    //             'username' => $validated['vendorname'],
    //             'email' => $validated['email'],
    //             'mobile' => $validated['mobile'],
    //             'role' => $validated['role'],
    //         ]);

    //         // Mise à jour du fournisseur
    //         $vendor->update([
    //             'vendorname' => $validated['vendorname'],
    //             'contactpersonname' => $validated['contactpersonname'],
    //             'businessregno' => $validated['businessregno'],
    //             'taxidnumber' => $validated['taxidnumber'],
    //             'businesscategory' => $validated['businesscategory'],
    //             'businessaddress' => $validated['businessaddress'],
    //             'businessemail' => $validated['businessemail'] ?? null,
    //             'bankname1' => $validated['bank_name_1'] ?? null,
    //             'bankaccount1' => $validated['bankaccount1'] ?? null,
    //             'bankname2' => $validated['bankname2'] ?? null,
    //             'bankaccount2' => $validated['bankaccount2'] ?? null,
    //             'accountholdername' => $validated['accountholdername'] ?? null,
    //         ]);

    //         return back()->with('success', 'Les informations du fournisseur ont été mises à jour avec succès.');
    //     } catch (\Throwable $t) {
    //         return back()->with('error', 'Une erreur s\'est produite : ' . $t->getMessage());
    //     }
    // }

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
