<?php

namespace App\Http\Controllers;

use App\Models\agence;
use App\Models\employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\New_;

class AgenceController extends Controller
{
    //

    public function createAgence(Request $request)
    {
        try {
            // Valider les données envoyées
            $validated = $request->validate([
                'agent_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'agency_code' => 'required|string|max:255|unique:agences',
            ]);

            // Créer une nouvelle agence avec les données validées
            $agence = Agence::create([
                'agent_name' => $validated['agent_name'],
                'description' => $validated['description'],
                'agency_code' => $validated['agency_code'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $agence
            ], 201); // Code HTTP 201 pour indiquer que la ressource a été créée
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de l\'agence',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function createAgencies(Request $request)
    {
        try {
            // Valider les données envoyées
            $validated = $request->validate([
                'agent_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'agency_code' => 'required|string|max:255|unique:agences',
            ]);

            // Créer une nouvelle agence avec les données validées
            $agence = Agence::create([
                'agent_name' => $validated['agent_name'],
                'description' => $validated['description'],
                'agency_code' => $validated['agency_code'],
            ]);
            return back()->with('success', 'Agency created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the agency.');
        }
    }
    public function editAgencies(Request $request)
    {
        try {
            // Valider les données envoyées
            $validated = $request->validate([
                'agent_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'agency_code' => 'required|string|max:255|unique:agences,agency_code,' . $request->agenceId
            ]);

            // Trouver l'agence par son ID
            $agence = Agence::find($request->agenceId);

            if (!$agence) {
                return back()->with('error', 'Agency not found');
            }

            // Mettre à jour les données de l'agence
            $agence->update([
                'agent_name' => $validated['agent_name'],
                'description' => $validated['description'],
                'agency_code' => $validated['agency_code'],
            ]);

            return back()->with('success', 'Agency updated successfully');
        } catch (\Exception $e) {
            // Retourner une réponse JSON pour un débogage supplémentaire, si nécessaire
            return back()->with('error', 'An error occurred while editing the agency')->withInput();
        }
    }

    public function deleteAgencies(Request $request)
    {
        try {
            // Valider les données envoyées
            $validated = $request->validate([
                'agenceId' => 'required|exists:agences,id',
            ]);

            // Trouver l'agence par son ID
            $agence = Agence::find($validated['agenceId']);

            if (!$agence) {
                return back()->with('error', 'Agency not found');
            }

            // Supprimer l'agence
            $agence->delete();

            return back()->with('success', 'Agency successfully deleted');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the agency: ' . $e->getMessage());
        }
    }

    public function editAgence(Request $request)
    {
        try {
            // Valider les données envoyées
            $validated = $request->validate([
                'agent_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'agency_code' => 'required|string|max:255|unique:agences,agency_code,' . $request->agenceId
            ]);


            // Trouver l'agence par son ID
            $agence = Agence::find($request->agenceId);

            if (!$agence) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agence non trouvée'
                ], 404); // Code HTTP 404 pour une ressource non trouvée
            }

            // Mettre à jour les données de l'agence
            $agence->update([
                'agent_name' => $validated['agent_name'],
                'description' => $validated['description'],
                'agency_code' => $validated['agency_code'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $agence
            ], 200); // Code HTTP 200 pour une mise à jour réussie
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'édition de l\'agence',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500); // Code HTTP 500 pour une erreur serveur
        }
    }

    public function deleteAgence(Request $request)
    {
        try {

            $validated = $request->validate([
                'agenceId' => 'required|exists:agences,id',
            ]);

            //   dd($request->agenceId);

            // Find the agency by its ID
            $agence = Agence::find($request->agenceId);

            if (!$agence) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agency not found'
                ], 404); // HTTP 404 for resource not found
            }

            // Delete the agency
            $agence->delete();

            return response()->json([
                'success' => true,
                'message' => 'Agency successfully deleted'
            ], 200); // HTTP 200 for successful deletion

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the agency',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500); // HTTP 500 for server error
        }
    }




    public function getAllAgencesInfo()
    {
        try {
            $agences = Agence::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $agences
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue",
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function reset()
    {
        // Récupérer tous les utilisateurs
        // $users = User::all();
        $users = employee::all();

        // Parcourir chaque utilisateur et réinitialiser leur mot de passe
        foreach ($users as $user) {
            $user->password = Hash::make('12345678'); // Appliquer le hash au nouveau mot de passe
            $user->save(); // Sauvegarder les modifications
        }

        return "Mot de passe réinitialisé pour tous les utilisateurs.";
    }

    public function searchAgences(Request $request)
    {
        try {
            $searchTerm = $request->input('search');
            if (!$searchTerm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez fournir un terme de recherche.'
                ], 400);
            }
            $agences = agence::orderBy('id', 'desc')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('agent_name', 'like', '%' . $searchTerm . '%')
                        ->orwhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('agency_code', 'like', '%' . $searchTerm . '%');
                })->get();

            return response()->json([
                'success' => true,
                'agences' => $agences
            ], 200);
        } catch (\Throwable $t) {
            Log::info('An error Occured' . $t->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error Occured',
                'error' => $t->getMessage()
            ], 500);
        }
    }
}
