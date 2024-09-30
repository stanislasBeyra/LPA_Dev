<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
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

        // Récupérer le produit pour vérifier la quantité en stock
        $product = Product::find($validatedData['product_id']);

        // Vérifier la quantité en stock
        if ($product->stock < $validatedData['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock for the requested product.',
                'available_quantity' => $product->stock // Retourner la quantité disponible
            ], 400);
        }

        // Vérifier si le produit est déjà dans le panier de l'utilisateur
        $existingCart = Cart::where('user_id', $user->id)
            ->where('product_id', $validatedData['product_id'])
            ->first();

        if ($existingCart) {
            // Si le produit est déjà dans le panier, on met à jour la quantité
            $existingCart->quantity += $validatedData['quantity'];

            // Vérifier la quantité en stock après mise à jour
            if ($product->stock < $existingCart->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for the requested product after update.',
                    'available_quantity' => $product->stock
                ], 400);
            }

            // Mettre à jour la quantité dans le panier
            $existingCart->save();
        }
            // Si le produit n'est pas dans le panier, on l'ajoute
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity']
            ]);
       

        // Diminuer le stock du produit
        $product->stock -= $validatedData['quantity'];

        // Vérifier si le stock est à 0 et mettre le produit en rupture de stock si nécessaire
        if ($product->stock == 0) {
            $product->status = 'out of stock'; // Assurez-vous d'avoir un champ "status" ou quelque chose de similaire
        }

        // Sauvegarder les changements sur le produit
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart, and stock updated.',
            'cart' => $existingCart ?? $cart,
            'remaining_stock' => $product->stock
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
            'message' => 'An error has occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
}


//     public function addToCart(Request $request)
// {
//     try {
//         // Vérifier si l'utilisateur est authentifié
//         $user = Auth::user();
//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'You are not authenticated.'
//             ], 401);
//         }

//         // Validation des données de la requête
//         $validatedData = $request->validate([
//             'product_id' => 'required|exists:products,id',
//             'quantity' => 'required|integer|min:1',
//         ]);

//         // Récupérer le produit pour vérifier la quantité en stock
//         $product = Product::find($validatedData['product_id']);

//         // Vérifier la quantité en stock
//         if ($product->stock < $validatedData['quantity']) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Insufficient stock for the requested product.',
//                 'available_quantity' => $product->stock // Optionnel : retourner la quantité disponible
//             ], 400);
//         }

//         // Vérifier si le produit est déjà dans le panier de l'utilisateur
//         $existingCart = Cart::where('user_id', $user->id)
//             ->where('product_id', $validatedData['product_id'])
//             ->first();

//         if ($existingCart) {
//             // Si le produit est déjà dans le panier, on met à jour la quantité
//             $existingCart->quantity += $validatedData['quantity'];

//             // Vérifier à nouveau la quantité en stock après mise à jour
//             if ($product->stock < $existingCart->quantity) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Insufficient stock for the requested product after update.',
//                     'available_quantity' => $product->stock
//                 ], 400);
//             }

//             $existingCart->save();

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Product quantity updated in cart.',
//                 'cart' => $existingCart
//             ], 200);
//         }

//         // Si le produit n'est pas dans le panier, on l'ajoute
//         $cart = Cart::create([
//             'user_id' => $user->id,
//             'product_id' => $validatedData['product_id'],
//             'quantity' => $validatedData['quantity']
//         ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'Product added to cart.',
//             'cart' => $cart
//         ], 200);
//     } catch (\Illuminate\Validation\ValidationException $e) {
//         // Gestion des erreurs de validation
//         return response()->json([
//             'success' => false,
//             'message' => 'Validation failed.',
//             'errors' => $e->errors()
//         ], 422);
//     } catch (\Exception $e) {
//         // Gestion des autres exceptions
//         return response()->json([
//             'success' => false,
//             'message' => 'An error has occurred.',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

    public function showCartProducts()
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

        // Récupérer tous les produits du panier de l'utilisateur
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Your cart is empty.',
                'cart' => []
            ], 200);
        }

        return response()->json([
            'success' => true,
            'cart' => $cartItems
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function updateCartProduct(Request $request)
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
            'quantity' => 'required|integer|min:1', // La quantité doit être un entier positif
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

        // Mettre à jour la quantité du produit
        $cartItem->quantity = $validatedData['quantity'];
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Product quantity updated successfully.',
            'cart' => $cartItem
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

/// function pour  vider tout le panier du utilise

public function destroyAllCartProducts() {
    try {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No items in the cart.'
            ], 404);
        }

        // Suppression de tous les articles du panier
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'All cart items have been deleted.'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error has occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
