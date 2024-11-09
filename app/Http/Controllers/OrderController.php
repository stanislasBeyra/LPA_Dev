<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order;
use App\Models\order_items;
use App\Models\payementperiodemode;
use App\Models\payementsalaires;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //

    public function payement($orderid, $total_amount, $period, $month_1 = null, $month_2 = null, $month_3 = null, $month_4 = null, $month_5 = null, $month_6 = null)
    {
        // Validation des données
        if (is_null($total_amount) || !is_numeric($total_amount) || $total_amount < 0) {
            return response()->json(['message' => 'Le montant total est requis et doit être un nombre positif.'], 400);
        }

        if (is_null($period) || !is_integer($period) || $period < 0 || $period > 6) {
            return response()->json(['message' => 'La période est requise et doit être un entier entre 0 et 6.'], 400);
        }

        // Si la période est 0, définissons la période à 6 par défaut
        $period = $period === 0 ? 6 : $period;

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour effectuer un paiement.',

            ]);
        }
        // Créer un paiement
        $payment = new payementperiodemode();
        $payment->user_id = $user->id;
        $payment->order_id = $orderid;
        $payment->total_amount = $total_amount;
        $payment->period = $period;

        // Récupération des montants des mois
        $months = [
            $month_1 ?? 0,
            $month_2 ?? 0,
            $month_3 ?? 0,
            $month_4 ?? 0,
            $month_5 ?? 0,
            $month_6 ?? 0,
        ];

        // Vérifier si la somme des montants est supérieure au total_amount

        $totalMonthsAmount = array_sum($months);

        // Si la somme des montants pour chaque mois est nulle, répartir le montant
        if ($totalMonthsAmount === 0) {
            $payment->distributeAmountOverMonths($total_amount, $period);
        } else {
            // Affectation des montants
            $payment->month_1 = $month_1;
            if ($period >= 2) $payment->month_2 = $month_2;
            if ($period >= 3) $payment->month_3 = $month_3;
            if ($period >= 4) $payment->month_4 = $month_4;
            if ($period >= 5) $payment->month_5 = $month_5;
            if ($period == 6) $payment->month_6 = $month_6;
        }

        if ($totalMonthsAmount < $total_amount) {
            return response()->json([
                'message' => 'La somme est inferieur au montant dachat.Veuillez bien renseignez les champ.',
                'data' => $payment
            ], 400); // 400 Bad Request
        }

        // Sauvegarder le paiement
        $payment->save();

        // Retourner une réponse JSON avec le paiement créé
        return response()->json([
            'message' => 'Paiement ajouté avec succès.',
            'data' => $payment
        ], 201); // 201 Created
    }



    public function placeOrder(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'period' => 'required|integer|min:0|max:6',
                'month_1' => 'nullable|numeric|min:0',
                'month_2' => 'nullable|numeric|min:0',
                'month_3' => 'nullable|numeric|min:0',
                'month_4' => 'nullable|numeric|min:0',
                'month_5' => 'nullable|numeric|min:0',
                'month_6' => 'nullable|numeric|min:0',
            ]);
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
            // Récupérer les salaires des trois derniers mois
            $salaries = payementsalaires::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->where('created_at', '<=', now())
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->pluck('amount');

            // Vérifier s'il y a au moins 3 salaires
            if ($salaries->count() < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'You should have obtained at least 3 salaries.',
                ], 400);
            }

            // Calculer la somme des salaires
            $totalSalaries = $salaries->sum();

            // Calculer la moyenne des salaires sur 3 mois
            $averageSalary = $totalSalaries / 3;
            $newamountsalarie=$averageSalary/3;

            // Vérifier si la moyenne des salaires est supérieure ou égale au montant de la commande
            if ($newamountsalarie < $totalAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your salary does not allow you to exceed the order amount.',
                ], 400);
            }

            // Créer une commande principale (table 'orders')
            $order = new Order();
            $order->user_id = $user->id;
            $order->total = $totalAmount;
            $order->status = 1; // Commande en attente
            $order->save();

            // Ajouter chaque produit dans la table 'order_items'
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);

                $orderItem = new order_items();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item->quantity;
                $orderItem->total = $product->price * $item->quantity;
                $orderItem->save();

                // Réduire le stock du produit
                $product->stock -= $item->quantity;

                // generation du mode de paiemt
                // $modepaiement=new payementperiodemode();
                // $modepaiement->payement(2000,3);


                $product->save();
            }

            $this->payement(
                $order->id,
                $order->total,
                $validatedData['period'],
                $validatedData['month_1'] ?? null,
                $validatedData['month_2'] ?? null,
                $validatedData['month_3'] ?? null,
                $validatedData['month_4'] ?? null,
                $validatedData['month_5'] ?? null,
                $validatedData['month_6'] ?? null
            );
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


    public function lastplaceOrder(Request $request)
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
            $order->total = $totalAmount;
            $order->status = 1; // Commande en attente
            $order->save();

            // Ajouter chaque produit dans la table 'order_items'
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);

                $orderItem = new order_items();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item->quantity;
                $orderItem->total = $product->price * $item->quantity;
                $orderItem->save();

                // Réduire le stock du produit
                $product->stock -= $item->quantity;

                // generation du mode de paiemt
                // $modepaiement=new payementperiodemode();
                // $modepaiement->payement(2000,3);


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


    public function showOrderProducts()
    {
        try {
            $user = Auth::user();

            // Récupérer la commande par son ID et s'assurer qu'elle appartient à l'utilisateur
            $order = Order::with('orderItems.product')
                ->where('user_id', $user->id)
                ->firstOrFail(); // Cela lancera une exception si la commande n'est pas trouvée

            // Retourner les détails de la commande avec les produits
            return response()->json([
                'success' => true,
                // 'order_id' => $order->id,
                // 'user_id' => $order->user_id,
                'total' => $order->total,
                'status' => $order->status,
                'products' => $order->orderItems->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'total' => $item->total,
                        'product_name' => $item->product->product_name,
                        "product_images1" => $item->product->product_images1,
                        "product_images2" => $item->product->product_images1,
                        "product_images3" => $item->product->product_images3,
                    ];
                }),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
