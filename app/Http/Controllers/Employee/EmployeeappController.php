<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\employee;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeappController extends Controller
{
    //

    public function Clientlogin(Request $request)
    {
        try {
            // Validation des données d'entrée
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $user = employee::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Adresse email incorrecte',
                ], 401);
            }
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les informations d\'identification sont incorrectes',
                ], 401);
            }
            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error has occurred.',
                'error' => $e->getMessage()
            ], 401);
        }
    }

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
                'message' => 'An error has occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function getProductsByVendorId(Request $request)
    // {
    //     try {
    //         // Vérifier si le vendeur existe
    //         $vendor = User::find($request->vendorId);

    //         if (!$vendor) { // Assure-toi que c'est un vendeur
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Vendor not found or not authorized.'
    //             ], 404);
    //         }

    //         // Récupérer les produits associés au vendeur
    //         $products = Product::where('vendor_id', $vendor->id)
    //             ->whereNull('deleted_at')
    //             ->latest()
    //             ->take(10)
    //             ->get();

    //         // Retourner une réponse JSON avec les produits
    //         return response()->json([
    //             'success' => true,
    //             'products' => $products
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Gérer les exceptions et retourner une réponse JSON d'erreur
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

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
        $products = Product::with('category')
            ->where('vendor_id', $vendor->id)
            ->whereNull('deleted_at')
            ->latest()
            // ->take(50)
            ->get();

        // Créer un tableau de réponse avec les détails des produits
        $response = $products->map(function ($product) {
            return [
                "id" => $product->id,
                'product_name' => $product->product_name,
                "product_description" => $product->product_description,
                'stock' => $product->stock,
                'price' => $product->price,
                'categorie_id' => $product->category->id ?? null,
                'vendor_id' => $product->vendor_id,
                'status' => $product->status,
                'product_images1' => $product->product_images1,
                'product_images2' => $product->product_images2,
                'product_images3' => $product->product_images3,
                'created_at' => $product->created_at,
                "categories_name" => $product->category->categories_name ?? null,
                'categories_description' => $product->category->categories_description ?? null
            ];
        });

        // Retourner une réponse JSON avec les produits
        return response()->json([
            'success' => true,
            'products' => $response
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


public function appAllproduct(Request $request)
{
    try {
     
        $products = Product::with('category')
            ->whereNull('deleted_at')
            ->latest()
            ->get();
        $response = $products->map(function ($product) {
            return [
                "id" => $product->id,
                'product_name' => $product->product_name,
                "product_description" => $product->product_description,
                'stock' => $product->stock,
                'price' => $product->price,
                'categorie_id' => $product->category->id ?? null,
                'vendor_id' => $product->vendor_id,
                'status' => $product->status,
                'product_images1' => $product->product_images1,
                'product_images2' => $product->product_images2,
                'product_images3' => $product->product_images3,
                'created_at' => $product->created_at,
                "categories_name" => $product->category->categories_name ?? null,
                'categories_description' => $product->category->categories_description ?? null
            ];
        });

        // Retourner une réponse JSON avec les produits
        return response()->json([
            'success' => true,
            'products' => $response
        ], 200);

    } catch (\Exception $e) {
        // Gérer les exceptions et retourner une réponse JSON d'erreur
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage(),
            'line'=>$e->getLine()
        ], 500);
    }
}


//     public function getProductsByVendorId(Request $request)
// {
//     try {
//         // Vérifier si le vendeur existe
//         $vendor = User::find($request->vendorId);

//         if (!$vendor) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Vendor not found or not authorized.'
//             ], 404);
//         }

//         // Récupérer les produits associés au vendeur
//         $products = Product::with('category')
//             ->where('vendor_id', $vendor->id)
//             ->whereNull('deleted_at')
//             ->latest()
//             ->take(10)
//             ->get();

//         // Transformer chaque produit dans le format souhaité
//         $response = $products->map(function ($product) {
//             return [
//                 "product_id" => $product->id,
//                 'product_name' => $product->product_name,
//                 "product_description" => $product->product_description,
//                 'stock' => $product->stock,
//                 'price' => $product->price,
//                 'categorie_id' => $product->category->id ?? null,
//                 'vendor_id' => $product->vendor_id,
//                 'status' => $product->status,
//                 'product_images1' => $product->product_images1,
//                 'product_images2' => $product->product_images2,
//                 'product_images3' => $product->product_images3,
//                 'created_at' => $product->created_at,
//                 "categories_name" => $product->category->categories_name ?? null,
//                 'categories_description' => $product->category->categories_description ?? null
//             ];
//         });

//         // Retourner une réponse JSON avec uniquement les produits
//         return response()->json($response, 200);

//     } catch (\Exception $e) {
//         // Gérer les exceptions et retourner une réponse JSON d'erreur
//         return response()->json([
//             'success' => false,
//             'message' => 'An unexpected error occurred.',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

}
