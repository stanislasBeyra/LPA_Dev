<?php

namespace App\Http\Controllers;

use App\Models\agence;
use Illuminate\Http\Request;

class AgenceController extends Controller
{
    //

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
