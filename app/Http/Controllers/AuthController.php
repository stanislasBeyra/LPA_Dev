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

class AuthController extends Controller
{
    //
    protected $emailController;

    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }


    public function generateusername($characters, $length=10){
        $charactersLength = strlen($characters);
        $username='';
        for ($i = 0; $i < $length; $i++) {
            $username .= $characters[rand(0, $charactersLength - 1)];
        }
        return $username;

    }

    public function  testeUsernamegerate(){
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
         //   Mail::to($employee->email)->send(new SendUserCredentialsMail($employee, $data['password']));

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'passwor'=>$data['password'],
                'message' => 'Employee created successfully',
                'employee' => $employee
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
          //  Mail::to($user->email)->send(new SendUserCredentialsMail($user, $data['password']));

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'passwor'=>$data['password'],
                'message' => $data['roleregister'] . ' created successfully',
                'user' => $user
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

    public function SubmitRegister(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile' => 'required|string|max:255',
                'salary' => 'required|numeric|min:0',
                'role' => 'required|integer|in:1,2,3',

                'roleregister' => 'nullable|string|',
            ]);
            Log::info('resister', [
                'usedata' => $request->all(),

            ]);
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
            }
            elseif ($request->roleregister == 'Admin' || $request->roleregister == 'Vendor') {

                return $this->registerVendorAndAdmin($data);
            }
            else {
                return response()->json([
                    'success' => false,
                    'password'=>$password,
                    'message' => "This role is not available"
                ],400);
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
        $user->tokens()->delete();

        // Création d'un nouveau token d'authentification
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
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
            $validatedData = $request->validate([
                'firstname' => 'sometimes|required|string|max:255',
                'lastname' => 'sometimes|required|string|max:255',
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . Auth::id(),
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . Auth::id(),
                'mobile' => 'sometimes|required|string|max:15',
                'password' => 'sometimes|required|string|min:8|confirmed',
                'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            ]);

            $user = $request->user(); // Obtient l'utilisateur actuel

            //dd($validatedData);
            // Gestion de l'avatar
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarPath = $avatar->store('avatars', 'public'); // Stockage dans le répertoire 'images' du disque 'public'
                $user->avatar = $avatarPath;
            }

            // Mise à jour des données utilisateur
            if (isset($validatedData['firstname'])) {
                $user->firstname = $validatedData['firstname'];
            }
            if (isset($validatedData['lastname'])) {
                $user->lastname = $validatedData['lastname'];
            }
            if (isset($validatedData['username'])) {
                $user->username = $validatedData['username'];
            }
            if (isset($validatedData['email'])) {
                $user->email = $validatedData['email'];
            }
            if (isset($validatedData['mobile'])) {
                $user->mobile = $validatedData['mobile'];
            }
            if (isset($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
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
        }
    }



    // public function updateProfile(Request $request)
    // {
    //     try {
    //         // Validation des données
    //         $request->validate([
    //             'firstname' => 'sometimes|required|string|max:255',
    //             'lastname' => 'sometimes|required|string|max:255',
    //             'username' => 'sometimes|required|string|max:255|unique:users,username,' . Auth::id(),
    //             'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . Auth::id(),
    //             'mobile' => 'sometimes|required|string|max:255',
    //             'password' => 'sometimes|required|string|min:8|confirmed',
    //             'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour l'avatar
    //         ]);

    //       //  dd($request->avatar);
    //         // Récupération de l'utilisateur authentifié
    //         $user = Auth::user();

    //         // Mise à jour des informations de l'utilisateur
    //         $user->firstname = $request->input('firstname', $user->firstname);
    //         $user->lastname = $request->input('lastname', $user->lastname);
    //         $user->username = $request->input('username', $user->username);
    //         $user->email = $request->input('email', $user->email);
    //         $user->mobile = $request->input('mobile', $user->mobile);

    //         if ($request->filled('password')) {
    //             $user->password = Hash::make($request->password);
    //         }

    //         // Gestion de l'avatar
    //         if ($request->hasFile('avatar')) {
    //             // Supprimer l'ancien avatar s'il existe
    //             $this->deleteOldAvatar($user->avatar);

    //             // Stockage du nouveau fichier
    //             $filename = $request->file('avatar')->store('avatars', 'public');
    //             $user->avatar = $filename;
    //         }

    //         // Sauvegarde des modifications
    //         $user->save();

    //         // Réponse en cas de succès
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Profil mis à jour avec succès',
    //             'user' => $user
    //         ], 200);

    //     } catch (\Exception $e) {
    //         // Réponse en cas d'erreur
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la mise à jour du profil',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    private function deleteOldAvatar($avatarPath)
    {
        if ($avatarPath && Facade::getFacadeApplication()['files']->exists('public/avatars/' . $avatarPath)) {
            Facade::getFacadeApplication()['files']->delete('public/avatars/' . $avatarPath);
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
}
