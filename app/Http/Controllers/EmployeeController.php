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

    public function getEmployeeListe(){
        try{
            $employees = employee::with('agence')->latest()->paginate(8);

            return $employees;

        }catch(\Exception $e){

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

    // public function getallEmploye(){
    //     try{
    //         $employees = employee::all();
    //         return response()->json([
    //             'success' => true,
    //             'employees' => $employees
    //             ], 200);
    //     }catch(\Exception $e){
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la récupération des employés',
    //             'error' => $e->getMessage()
    //             ], 500);
    //     }

    // }

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
}
