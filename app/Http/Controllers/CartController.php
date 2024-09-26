<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //

    public function addToCart(Request $request)
    {
        try {
            // Vérifier si l'utilisateur est authentifié
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.'
                ], 401);
            }

            // Validation des données de la requête
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',  
            ]);

            // Vérifier si le produit est déjà dans le panier de l'utilisateur
            $existingCart = Cart::where('user_id', $user->id)
                ->where('product_id', $validatedData['product_id'])
                ->first();

            if ($existingCart) {
                // Si le produit est déjà dans le panier, on met à jour la quantité
                $existingCart->quantity += $validatedData['quantity'];
                $existingCart->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Product quantity updated in cart.',
                    'cart' => $existingCart
                ], 200);
            }
            // Si le produit n'est pas dans le panier, on l'ajoute
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart.',
                'cart' => $cart
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres exceptions
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart(Request $request)
{
    try {
        // Vérifier si l'utilisateur est authentifié
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated.'
            ], 401);
        }

        // Validation des données de la requête
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id', // Vérifie que le produit existe dans la table products
        ]);

        // Trouver le produit dans le panier de l'utilisateur
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('product_id', $validatedData['product_id'])
                        ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in the cart.'
            ], 404);
        }

        // Supprimer le produit du panier
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart.'
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Gestion des erreurs de validation
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        // Gestion des autres exceptions
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
