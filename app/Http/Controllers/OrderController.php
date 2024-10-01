<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order;
use App\Models\order_items;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //

    public function placeOrder(Request $request)
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

        // Récupérer les articles du panier pour l'utilisateur
        $cartItems = Cart::where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ], 400);
        }

        // Calculer le total de la commande
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            if (!$product || $product->stock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for product ID {$item->product_id}."
                ], 400);
            }

            $totalAmount += $product->price * $item->quantity;
        }

        // Créer une commande principale (table 'orders')
        $order = new Order();
        $order->user_id = $user->id;
        $order->product_id = $user->id;
        $order->total = $totalAmount;
        $order->status = 1; // Commande en attente
        $order->save();

        // Ajouter chaque produit dans la table 'order_items'
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);

            dd($product->id);
            $orderItem = new order_items();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $item->quantity;
            $orderItem->total = $product->price * $item->quantity;
            $orderItem->save();

            // Réduire le stock du produit
            $product->stock -= $item->quantity;
            $product->save();
        }

        // Vider le panier après la commande
        Cart::where('user_id', $user->id)->delete();

        // Retourner une réponse JSON après la création réussie
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
            'total' => $totalAmount
        ], 201);

    } catch (\Exception $e) {
        // Gestion des erreurs
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // public function placeOrder(Request $request)
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

    //         // Récupérer les articles du panier pour l'utilisateur
    //         $cartItems = Cart::where('user_id', $user->id)->get();
    //         if ($cartItems->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Your cart is empty.'
    //             ], 400);
    //         }

    //         $totalAmount = 0;

    //         foreach ($cartItems as $item) {
    //             $product = Product::find($item->product_id);
    //             if (!$product) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Product not found.'
    //                 ], 404);
    //             }

    //             // Vérifier si la quantité demandée est disponible
    //             if ($product->stock < $item->quantity) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => "Not enough stock for product ID {$product->id}.",
    //                     'available_quantity' => $product->stock
    //                 ], 400);
    //             }

    //             // Calculer le total pour cet article
    //             $totalAmount += $product->price * $item->quantity;
    //         }

    //         // Créer une commande pour chaque article du panier
    //         foreach ($cartItems as $item) {
    //             $product = Product::find($item->product_id);

    //             $order = new Order();
    //             $order->user_id = $user->id;
    //             $order->product_id = $product->id;
    //             $order->total = $product->price * $item->quantity;
    //             $order->save();

    //             // Réduire le stock du produit
    //             $product->stock -= $item->quantity;
    //             $product->save();
    //         }

    //         // Supprimer les articles du panier après la commande
    //         Cart::where('user_id', $user->id)->delete();

    //         // Retourner une réponse JSON après la création réussie
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Order placed successfully.',
    //             'total' => $totalAmount
    //         ], 201);

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
    //             'message' => 'An unexpected error occurred.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


}
