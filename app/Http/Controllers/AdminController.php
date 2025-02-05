<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\order;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    //


    public function index()
    {
        // Récupérer le total des employés
        $totalEmployees = Employee::count();  // Compte tous les employés

        $totalVendor = User::where('role', 3)->count();

        // Récupérer le total des commandes avec le statut 1
        $totalpendingorder = Order::where('status', 1)->count();
        $totalPendingAmount = Order::where('status', 1)->sum('total');


        // Récupérer le total des commandes avec le statut 3 Approuver
        $totalrefuseorder = Order::where('status', 3)->count();
        $totalRefuseAmount = Order::where('status', 3)->sum('total');

        // Récupérer le total des commandes avec le statut 4 Refuser
        $totalvalidateorder = Order::where('status', 4)->count();
        $totalValidateAmount = Order::where('status', 4)->sum('total');

        $totalOrdersToday = Order::whereDate('created_at', today())->count(); // Nombre de commandes d'aujourd'hui
        $totalAmountToday = Order::whereDate('created_at', today())->sum('total'); // Somme des montants des commandes d'aujourd'hui



        // Passer cette donnée à la vue
        return view('index', compact('totalEmployees', 'totalVendor', 'totalpendingorder', 'totalvalidateorder', 'totalrefuseorder', 'totalPendingAmount', 'totalRefuseAmount', 'totalValidateAmount','totalOrdersToday','totalAmountToday'));
    }

    // public function index()
    // {
    //     // Récupérer le total des employés
    //     $totalEmployees = employee::count();  // Compte tous les employés

    //     $totalVendor = User::where('role', 3)->count();

    //     $totalpendingorder = order::where('status', 1)->count();

    //     // Récupérer le total des commandes avec le statut 2
    //     $totalrefuseorder = Order::where('status', 3)->count();

    //     // Récupérer le total des commandes avec le statut 3
    //     $totalvalidateorder = Order::where('status', 4)->count();


    //     // Passer cette donnée à la vue
    //     return view('index', compact('totalEmployees', 'totalVendor', 'totalpendingorder', 'totalvalidateorder', 'totalrefuseorder'));
    // }

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

            if (!$admin) {
                return back()->with('error', 'Admin Not Found');
            }

            // Validate the input data
            $validated = $request->validate([
                'firstname' => 'sometimes|required|string|max:255',
                'lastname' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $admin->id,
                'mobile' => 'sometimes|required|string|max:20|unique:users,mobile,' . $admin->id,
                'username' => 'sometimes|required|string|max:100|unique:users,username,' . $admin->id,
                'role' => 'sometimes|',
                'passwordreset'=>'nullable'
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
            ]);

            // dd($validated);

             if ($validated['passwordreset'] === 'true') {
                $admin->update([
                    'password' => Hash::make('12345678'),
                ]);
            
                return back()->with('success', 'The admin\'s Password reset successful.');
            }
            
            
            // Update the admin fields
            $admin->update([
                'firstname' => $validated['firstname'] ?? $admin->firstname,
                'lastname' => $validated['lastname'] ?? $admin->lastname,
                'email' => $validated['email'] ?? $admin->email,
                'mobile' => $validated['mobile'] ?? $admin->mobile,
                'username' => $validated['username'] ?? $admin->username,
                'role' => $validated['role'] ?? $admin->role
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
            if (!$admin) {
                return back()->with('error', 'Admin Not Found');
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

    public function searchAdmin(Request $request)
    {
        try {
            $searchTerm = $request->input('search');

            if (!$searchTerm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez fournir un terme de recherche.'
                ], 400);
            }
 
            // Remplacez 'role' par la colonne correcte pour identifier les administrateurs
            $admins = User::whereIN('role', [1, 5])
                ->orderby('id', 'desc')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('firstname', 'like', '%' . $searchTerm . '%')
                        ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                        ->orWhere('username', 'like', '%' . $searchTerm . '%')
                        ->orWhere('mobile', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%');
                })
                ->get();

            return response()->json([
                'success' => true,
                'admins' => $admins
            ], 200);
        } catch (\Throwable $t) {
            Log::info('Une erreur s\'est produite : ' . $t->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite : ' . $t->getMessage()
            ], 500);
        }
    }
}
