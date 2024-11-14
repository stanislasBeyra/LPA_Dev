<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendUserCredentialsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\EmailController;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    //
    protected $emailController;

    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }


    public function generateusername($characters, $length = 10)
    {
        $charactersLength = strlen($characters);
        $username = '';
        for ($i = 0; $i < $length; $i++) {
            $username .= $characters[rand(0, $charactersLength - 1)];
        }
        return $username;
    }

    public function  testeUsernamegerate()
    {
        return $this->generateusername('DidierJean');
    }

    function generateRandomPassword($length = 12)
    {
        // Ensemble de caractères pour générer le mot de passe
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Longueur du pool de caractères
        $charactersLength = strlen($characters);

        // Variable pour stocker le mot de passe généré
        $randomPassword = '';

        // Générer le mot de passe aléatoire
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomPassword;
    }

    public function EmployeeRegister($data)
    {
        try {
            // Création d'un nouvel employé
            $employee = Employee::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'net_salary' => $data['salary'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            // Envoi de l'email avec les informations d'identification
            $mail =   Mail::to($employee->email)->send(new SendUserCredentialsMail($employee, $data['password']));

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'passwor' => $data['password'],
                'message' => 'Employee created successfully',
                'employee' => $employee,
                'mail' => $mail
            ], 201);
        } catch (\Exception $e) {
            // Réponse en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => 'Error creating the employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerVendorAndAdmin($data)
    {
        try {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            // Envoi de l'email avec les informations d'identification
            $mail =   Mail::to($user->email)->send(new SendUserCredentialsMail($user, $data['password']));

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'passwor' => $data['password'],
                'message' => $data['roleregister'] . ' created successfully',
                'user' => $user,
                'mail' => $mail
            ], 201);
        } catch (\Exception $e) {
            // Réponse en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => 'Error creating the user',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function submitRegisterErrorMessage()
    {
        $messages = [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a number.',
            'role.required' => 'Role is required.',
            'roleregister.required' => 'Registration type is required.',
            'username.unique' => 'Username is already taken.',
            'email.unique' => 'Email address is already in use.',
            'mobile.unique' => 'Mobile number is already in use.',
        ];
        return $messages;
    }

    public function SubmitRegister(Request $request)
    {
        try {
            $message = $this->submitRegisterErrorMessage();
            // Validation des données communes à tous les rôles
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'salary' => 'required|numeric|min:0',
                'role' => 'required|integer|in:1,2,3',
                'roleregister' => 'required|string|in:Employee,Vendor,Admin',
            ], $message);

            // Validation conditionnelle pour Admin ou Vendor
            if ($request->roleregister === 'Admin' || $request->roleregister === 'Vendor') {
                $request->validate([
                    'username' => 'required|string|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile' => 'required|string|max:20|unique:users',
                ], $message);
            }

            // Validation conditionnelle pour Employee
            if ($request->roleregister === 'Employee') {
                $request->validate([
                    'username' => 'required|string|max:255|unique:employees',
                    'email' => 'required|string|email|max:255|unique:employees',
                    'mobile' => 'required|string|max:20|unique:employees',
                ], $message);
            }

            // Log des données reçues
            Log::info('register', [
                'userdata' => $request->all(),
            ]);

            // Génération d'un mot de passe aléatoire
            $password = $this->generateRandomPassword();

            $data = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'salary' => $request->salary,
                'password' => $password,
                'role' => $request->role,
                'roleregister' => $request->roleregister,
            ];

            // Création de l'utilisateur en fonction du rôle
            if ($request->roleregister == 'Employee') {
                return $this->EmployeeRegister($data);
            } elseif ($request->roleregister == 'Admin' || $request->roleregister == 'Vendor') {
                return $this->registerVendorAndAdmin($data);
            } else {
                return response()->json([
                    'success' => false,
                    'password' => $password,
                    'message' => "This role is not available"
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during form submission',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function login(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Rechercher l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Adresse email incorrecte',
            ], 401);
        }

        // Vérifier le mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Les informations d\'identification sont incorrectes',
            ], 401);
        }

        // Authentification réussie
        Auth::login($user);

        // Supprimer les jetons existants pour cet utilisateur
       // $user->tokens()->delete();

        // Création d'un nouveau token d'authentification
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'You are connected succeful',
            'token' => $token
        ], 200);
    }

    public function getUserConnectInfo()
    {
        try {
            // Check if the user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Connection information retrieved successfully',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving connection information',
            ], 500);
        }
    }






    public function updateProfile(Request $request)
    {
        try {
            // Validation des données
            // $validatedData = $request->validate([
            //     'firstname' => 'nullable|string|max:255',
            //     'lastname' => 'nullable|string|max:255',
            //     'username' => 'nullable|string|max:255|unique:users,username,' . Auth::id(),
            //     'email' => 'nullable|string|email|max:255|unique:users,email,' . Auth::id(),
            //     'mobile' => 'nullable|string|max:15',
            //     'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            // ]);

            $validatedData = $request->validate([
                'firstname' => 'nullable|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'username' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('users', 'username')->ignore(Auth::id()),
                ],
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore(Auth::id()),
                ],
                'mobile' => [
                    'nullable',
                    'string',
                    'max:15',
                    Rule::unique('users', 'mobile')->ignore(Auth::id()),
                ],
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            ]);

            $user = Auth::user(); // Obtient l'utilisateur actuel
            if(!$user){
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.',
                ]);
            }
            // Gestion de l'avatar
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarName = time() . '_' . $avatar->getClientOriginalName();
                $destinationPath = public_path('app/avatars');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $avatar->move($destinationPath, $avatarName);
                $user->avatar = 'avatars/' . $avatarName;
            }

            if (!empty($validatedData['firstname'])) {
                $user->firstname = $validatedData['firstname'];
            }
            if (!empty($validatedData['lastname'])) {
                $user->lastname = $validatedData['lastname'];
            }
            if (!empty($validatedData['username'])) {
                $user->username = $validatedData['username'];
            }
            if (!empty($validatedData['email'])) {
                $user->email = $validatedData['email'];
            }
            if (!empty($validatedData['mobile'])) {
                $user->mobile = $validatedData['mobile'];
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du profil.',
                'error' => $e->getMessage()
            ], 500);
        }catch(\Throwable $t){
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du profil.',
                'error' => $t->getMessage()
            ], 500);
        }
    }

    public function updateUserPassword(Request $request)
{
    try {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'current_password' => 'required|string', // Mot de passe actuel requis
            'password' => 'required|string|min:8|different:current_password|confirmed', // Nouveau mot de passe requis, différent de l'ancien et confirmé
        ]);

        $user = Auth::user(); // Obtient l'utilisateur actuel
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated.',
            ], 401); // Retourner un code de statut 401 pour l'authentification
        }

        // Vérifier le mot de passe actuel
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The current password is incorrect.',
            ], 403); // Retourner un code de statut 403 pour une erreur de validation
        }

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($validatedData['password']); // Utiliser Hash pour hacher le mot de passe
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);

    } catch (\Exception $e) {
        // Gestion des erreurs
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the profile.',
            'error' => $e->getMessage()
        ], 500);
    } catch (\Throwable $t) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the profile.',
            'error' => $t->getMessage()
        ], 500);
    }
}


// public function updateUserPassword(Request $request)
// {
//     try {
//         // Valider les données de la requête
//         $validatedData = $request->validate([
//             'currentpassword' => 'required|string',
//             'password' => 'required|string|min:8|different:currentpassword|confirmed',
//         ]);

//         $user = Auth::user(); 
//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'You are not authenticated.',
//             ], 401);
//         }

//         // Vérifier le mot de passe actuel
//         if (!Hash::check($validatedData['currentpassword'], $user->password)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'The current password is incorrect.',
//             ], 403);
//         }

//         // Mettre à jour le mot de passe de l'utilisateur
//         $user->password = Hash::make($validatedData['password']);
//         $user->save();

//         return response()->json([
//             'success' => true,
//             'message' => 'Password updated successfully.',
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'An error occurred while updating the profile.',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }


    public function getVendorList()
    {
        try {
            $vendors = User::where('role', 3)
                ->whereNull('deleted_at')
                ->get();
            return response()->json([
                'success' => true,
                'employees' => $vendors
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }





    public function delectuser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès',
        ], 200);
    }

    public function getCountUser() {
        try {
            // Authentification de l'utilisateur (peut être utilisé si nécessaire)
            $user = Auth::user();

            // Initialisation de la réponse
            $response = [];

            // Comptage des utilisateurs par rôle
            $countVendor = User::where('role', 3)->count();
            $countEmployer = Employee::count(); // Correction de la capitalisation de la classe
            $countAdmin = User::where('role', 1)->count();

            // Préparation des données de réponse
            $response = [
                'countVendor' => $countVendor,
                'countEmployer' => $countEmployer,
                'countAdmin' => $countAdmin,
            ];

            // Retour de la réponse en JSON
            return response()->json([
                'success' => true, // Corrigé pour refléter le succès
                'message' => 'User count successful', // Correction de la faute de frappe
                'data' => $response
            ], 200);

        } catch (\Exception $e) {
            // Gestion des exceptions
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function AdminupdateCustomerinfo(Request $request)
    {
        try {
            // Vérifier si l'ID de l'employé est passé dans la requête
            if (!$request->has('employeeId')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee ID is required'
                ], 400);
            }

            // Trouver l'employé par son ID
            $customer = employee::find($request->employeeId);

            // Vérifier si l'employé existe
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Validation des données (vous pouvez adapter les règles de validation selon les besoins)
            $validated = $request->validate([
                'firstname' => 'nullable|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255|unique:employees,username,' . $customer->id,
                'email' => 'nullable|email|unique:employees,email,' . $customer->id,
                'mobile' => 'nullable|string|max:255',
                'avatar' => 'nullable|image|max:2048', // Assurez-vous que l'avatar est une image si vous voulez le mettre à jour
                'status' => 'nullable|boolean',
                'net_salary' => 'nullable|numeric',
                'agencescode' => 'nullable|integer',
                'role' => 'nullable|integer|exists:roles,id', // Assurez-vous que le rôle existe dans la table roles
            ]);

            // Mise à jour des informations de l'employé
            $customer->update($validated);

            // Retourner la réponse de succès avec les nouvelles données de l'employé
            return response()->json([
                'success' => true,
                'message' => 'Employee information updated successfully',
                'data' => $customer
            ]);

        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the employee',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function Adminupdatevendorinfo(Request $request)
    {
        try {
            // Vérifier si l'ID de l'employé est passé dans la requête
            if (!$request->has('employeeId')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee ID is required'
                ], 400);
            }

            // Trouver l'employé par son ID
            $customer = User::find($request->employeeId);

            // Vérifier si l'employé existe
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Validation des données (vous pouvez adapter les règles de validation selon les besoins)
            $validated = $request->validate([
                'firstname' => 'nullable|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255|unique:employees,username,' . $customer->id,
                'email' => 'nullable|email|unique:employees,email,' . $customer->id,
                'mobile' => 'nullable|string|max:255',
                'avatar' => 'nullable|image|max:2048', // Assurez-vous que l'avatar est une image si vous voulez le mettre à jour
                'status' => 'nullable|boolean',
                'net_salary' => 'nullable|numeric',
                'agencescode' => 'nullable|string|max:255',
                'role' => 'nullable|integer|exists:roles,id', // Assurez-vous que le rôle existe dans la table roles
            ]);

            // Mise à jour des informations de l'employé
            $customer->update($validated);

            // Retourner la réponse de succès avec les nouvelles données de l'employé
            return response()->json([
                'success' => true,
                'message' => 'Employee information updated successfully',
                'data' => $customer
            ]);

        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the employee',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

}
