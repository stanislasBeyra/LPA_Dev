<?php

namespace App\Http\Controllers;

use App\Models\agence;
use Illuminate\Http\Request;

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
        // Validate that the agency ID is provided in the request
        $validated = $request->validate([
            'agenceId' => 'required|exists:agences,id', // Checks if the agency ID exists
        ]);

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
            $agences = Agence::all();
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
    
}
