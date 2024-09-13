<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Log;

class AuthController extends Controller
{
    //

    public function registeuser(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|integer|in:1,2,3',
            ]);

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            // Réponse en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
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


}
