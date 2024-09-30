<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeappController extends Controller
{
    //

    //liste des vendeur sur la vue des client
    public function getVendorListapp()
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

    public function getProductsByVendorId(Request $request)
    {
        try {
            // Vérifier si le vendeur existe
            $vendor = User::find($request->vendorId);

            if (!$vendor) { // Assure-toi que c'est un vendeur
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found or not authorized.'
                ], 404);
            }

            // Récupérer les produits associés au vendeur
            $products = Product::where('vendor_id', $vendor->id)
                ->whereNull('deleted_at')
                ->latest()
                ->take(10)
                ->get();

            // Retourner une réponse JSON avec les produits
            return response()->json([
                'success' => true,
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse JSON d'erreur
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
