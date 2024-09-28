<?php

namespace App\Http\Controllers;

use App\Models\employee; // Utilisation correcte du modèle Employee
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendUserCredentialsMail;
use Exception;

class EmployeeController extends Controller
{
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

    public function getallEmploye(){
        try{
            $employees = employee::all();
            return response()->json([
                'success' => true,
                'employees' => $employees
                ], 200);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des employés',
                'error' => $e->getMessage()
                ], 500);
        }

    }
}

