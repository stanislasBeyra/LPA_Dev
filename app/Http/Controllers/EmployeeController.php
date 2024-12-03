<?php

namespace App\Http\Controllers;

use App\Models\employee; // Utilisation correcte du modèle Employee
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendUserCredentialsMail;
use Exception;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{

    public function RegisterEmplyees(Request $request)
    {

        try {

            //  dd($request->status);
            // Validation des données
            $validatedData = $request->validate([
                'national_id' => 'required',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:employees', // Correction pour utiliser 'employees'
                'email' => 'required|string|email|max:255|unique:employees', // Correction pour utiliser 'employees'
                'mobile' => 'required|string|max:255',
                'agence_id' => 'required',
            ]);
            //  dd($validatedData);

            $status = $request->status == 'on' ? 1 : 0;




            // Création d'un nouvel employé
            $employee = employee::create([
                'national_id' => $validatedData['national_id'],
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'mobile' => $validatedData['mobile'],
                'status' => $status,
                'agencescode' => $validatedData['agence_id'],
                'password' => Hash::make('12345678'),
            ]);



            return back()->with('success', 'Employer enregistrer avec succces');
        } catch (Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function getEmployeeListe()
    {
        try {
            $employees = employee::with('agence')->latest()->paginate(8);

            return $employees;
        } catch (\Exception $e) {
        }
    }

    public function EmployeeRegister(Request $request)
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:employees', // Correction pour utiliser 'employees'
                'email' => 'required|string|email|max:255|unique:employees', // Correction pour utiliser 'employees'
                'mobile' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Création d'un nouvel employé
            $employee = employee::create([
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'mobile' => $validatedData['mobile'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Envoi de l'email avec les informations d'identification
            Mail::to($employee->email)->send(new SendUserCredentialsMail($employee, $validatedData['password']));

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'employee' => $employee
            ], 201);
        } catch (Exception $e) {
            // Réponse en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getAllEmploye()
    {
        try {
            // Retrieve all employees with their associated agencies
            $employees = Employee::with('agences')->get();

            return response()->json([
                'success' => true,
                'employees' => $employees
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while retrieving employees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateEmployesInfo(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'national_id' => 'nullable|string|unique:employees,mobile,' . $request->EmployeeId,
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|unique:employees,mobile,' . $request->EmployeeId,  // Ensure 'mobile' is unique except for this employee
            'mobile_two' => 'nullable|string|unique:employees,mobile2,' . $request->EmployeeId, // Same for 'mobile_two'
            'username' => 'nullable|string|max:255|unique:employees,username,' . $request->EmployeeId, // Ensure 'username' is unique except for this employee
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:employees,email,' . $request->EmployeeId, // Same for 'email'
            'agence_id' => 'nullable|exists:agences,id',
        ],[
            // Custom validation messages
            'national_id.unique' => 'The National ID number must be unique, except for this employee.',
            'mobile.unique' => 'The mobile number must be unique, except for this employee.',
            'mobile_two.unique' => 'The second mobile number must be unique, except for this employee.',
            'username.unique' => 'The username must be unique, except for this employee.',
            'email.unique' => 'The email address must be unique, except for this employee.',
            'email.email' => 'Please provide a valid email address.',
            'firstname.string' => 'The first name must be a valid string.',
            'lastname.string' => 'The last name must be a valid string.',
            'mobile.string' => 'The mobile number must be a valid string.',
            'mobile_two.string' => 'The second mobile number must be a valid string.',
            'username.string' => 'The username must be a valid string.',
            'middle_name.string' => 'The middle name must be a valid string.',
            'agence_id.exists' => 'The selected agency does not exist.',
            'firstname.max' => 'The first name must not exceed 255 characters.',
            'lastname.max' => 'The last name must not exceed 255 characters.',
            'username.max' => 'The username must not exceed 255 characters.',
            'email.max' => 'The email address must not exceed 255 characters.',
        ]);

        try {
            // Find the employee by ID
            $employee = Employee::findOrFail($request->EmployeeId);

            if(!$employee){
                return back()->with('error','Employee Not found');
            }
            // Update employee details
            $employee->update([
                'national_id' => $validated['national_id'] ?? $employee->national_id,
                'firstname' => $validated['firstname'] ?? $employee->firstname,
                'lastname' => $validated['lastname'] ?? $employee->lastname,
                'mobile' => $validated['mobile'] ?? $employee->mobile,
                'mobile2' => $validated['mobile_two'] ?? $employee->mobile2,
                'username' => $validated['username'] ?? $employee->username,
                'middle_name' => $validated['middle_name'] ?? $employee->middle_name,
                'email' => $validated['email'] ?? $employee->email,
                'agence_id' => $validated['agence_id'] ?? $employee->agence_id,
            ]);

            return back()->with('success', 'Employee updated successfully.');
        } catch (\Throwable $e) {
            Log::info('An error occurred: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the employee.');
        }
    }

    public function deleteEmployee(Request $request)
{
    try {
        // Find the employee by the provided ID
        $employee = Employee::find($request->EmployeeId);

        // Check if the employee exists
        if (!$employee) {
            return back()->with('error', 'This employee was not found.');
        }

        // Delete the employee
        $employee->delete();
        
        // Return success message
        return back()->with('success', 'Employee has been successfully deleted.');

    } catch (\Throwable $e) {
        // Log the error
        Log::error('An error occurred: ' . $e->getMessage());
        
        // Return error message
        return back()->with('error', 'An error occurred while deleting the employee.');
    }
}

}
