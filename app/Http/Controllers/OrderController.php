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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    //

    public static function generateOrderCode($length = 10)
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        do {
            $randomLetters = substr(str_shuffle($letters), 0, 3);

            $restOfCode = '';
            for ($i = 0; $i < $length - 3; $i++) {
                $restOfCode .= $numbers[rand(0, strlen($numbers) - 1)];
            }

            $orderCode = $randomLetters . $restOfCode;
            $existingOrder = Order::where('ordercode', $orderCode)->exists();
        } while ($existingOrder);
        return $orderCode;
    }


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
            // $salaries = payementsalaires::where('user_id', $user->id)
            // ->where('created_at', '>=', now()->subMonths(3))
            // ->where('created_at', '<=', now())
            // ->orderBy('created_at', 'desc')
            // ->take(3)
            // ->pluck('amount');

            // // Vérifier s'il y a au moins 3 salaires
            // if ($salaries->count() < 3) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You should have obtained at least 3 salaries.',
            //     ], 400);
            // }

            // // Calculer la somme des salaires
            // $totalSalaries = $salaries->sum();

            // // Calculer la moyenne des salaires sur 3 mois
            // $averageSalary = $totalSalaries / 3;
            // $newamountsalarie=$averageSalary/3;

            // // Vérifier si la moyenne des salaires est supérieure ou égale au montant de la commande
            // if ($newamountsalarie < $totalAmount) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Your salary does not allow you to exceed the order amount.',
            //     ], 400);
            // }

            // Créer une commande principale (table 'orders')
            $order = new Order();
            $order->user_id = $user->id;
            $order->total = $totalAmount;
            $order->ordercode = $this->generateOrderCode();
            $order->status = 1; // Commande en attente
            $order->save();

            // Ajouter chaque produit dans la table 'order_items'
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);

                $orderItem = new order_items();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item->quantity;
                $orderItem->vendor_id = $product->vendor_id;
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
            $order->ordercode = $this->generateOrderCode();
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



    public function newshowOrderProducts()
    {
        try {
            $user = Auth::user();

            // Récupérer toutes les commandes de l'utilisateur avec les produits associés
            $orders = Order::with('orderItems.product')
                ->where('user_id', $user->id)
                ->get(); // Utilisation de get() pour récupérer toutes les commandes

            // Vérifier si l'utilisateur a des commandes
            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No orders found for this user.'
                ], 404);
            }

            // Retourner les détails de toutes les commandes
            return response()->json([
                'success' => true,
                'orders' => $orders->map(function ($order) {
                    return [
                        'order_id' => $order->id,
                        'total' => $order->total,
                        'status' => $order->status,
                        'products' => $order->orderItems->map(function ($item) {
                            return [
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'total' => $item->total,
                                'product_name' => $item->product->product_name ?? null,
                                'product_images1' => $item->product->product_images1 ?? null,
                                'product_images2' => $item->product->product_images2 ?? null, // Correction ici
                                'product_images3' => $item->product->product_images3 ?? null,
                            ];
                        }),
                    ];
                }),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getallvendororder()
    {
        try {
            // Récupérer les orders avec leurs items et produits associés
            $orders = Order::with(['orderItems.product', 'employee'])
                ->where('status', '!=', 1)
                ->orderby('id', 'desc')
                ->get();

            $response = [];

            // Itérer sur chaque commande
            foreach ($orders as $order) {

                // Déterminer le statut de la commande sous forme de texte
                $statusText = match ($order->status) {
                    '1' => 'Pending',
                    '2' => 'Processing',
                    '3' => 'Validated',
                    '4' => 'Delivered',
                    '5' => 'Cancelled',
                    default => 'Unknown',
                };

                // Récupérer les données de chaque commande
                $orderData = [
                    'order_id' => $order->id,
                    'ordercode' => $order->ordercode,
                    'order_user_id' => $order->user_id,
                    'order_total' => $order->total,
                    'order_status' => $order->status,
                    'order_status_text' => $statusText, // Ajouter le texte du statut
                    'order_created_at' => $order->created_at,
                    'customer_name' => $order->employee ? $order->employee->username : null,
                    'customer_fisrtaname' => $order->employee ? $order->employee->firstname : null,
                    'customer_lastname' => $order->employee ? $order->employee->lastname : null,
                    'customer_mobile' => $order->employee ? $order->employee->mobile : null,
                    'customer_email' => $order->employee ? $order->employee->email : null,
                    'products' => [],
                ];

                // Itérer sur chaque order_item pour récupérer les produits
                foreach ($order->orderItems as $orderItem) {
                    $orderData['products'][] = [
                        'product1' => $orderItem->product ? $orderItem->product->product_images1 : null,
                        'product2' => $orderItem->product ? $orderItem->product->product_images2 : null,
                        'product3' => $orderItem->product ? $orderItem->product->product_images3 : null,
                        'product_name' => $orderItem->product ? $orderItem->product->product_name : null,
                        'product_price' => $orderItem->product ? $orderItem->product->price : 0,
                        'produ_quanty' => $orderItem->quantity,
                    ];
                }

                // Ajouter la commande avec les produits à la réponse
                $response[] = $orderData;
            }

            // Retourner la réponse au format JSON
            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }



    public function getOrders()
    {
        try {
            $vendor = Auth::user();
            //  dd($vendor->id);
            if (!$vendor) {
                if (!$vendor) {
                    return back()->with('error', 'Unauthorized access');
                }
            }

            $orderIds = order_items::where('vendor_id', $vendor->id)
                ->pluck('order_id');

            $orders = Order::with([
                'orderItems' => function ($query) use ($vendor) {
                    $query->where('vendor_id', $vendor->id); // Filtre sur `vendor_id`
                },
                'orderItems.product',
                'employee.agence'
            ])
                ->whereIn('id', $orderIds)
                ->orderBy('id', 'desc')
                ->get();


            $formattedOrders = $orders->map(function ($order) {
                return [
                    "orderId" => $order->id,
                    'orderTotal' => $order->total,
                    'ordercode' => $order->ordercode,
                    'orderStatus' => $order->status,
                    'ordercreated' => $order->created_at,
                    'employeefirstname' => $order->employee->firstname ?? null,
                    'employeelastname' => $order->employee->lastname ?? null,
                    'employeeusername' => $order->employee->username ?? null,
                    'employeemiddlename' => $order->employee->middle_name ?? null,
                    'employeeemail' => $order->employee->email ?? null,
                    'employeemobile' => $order->employee->mobile ?? null,
                    'employeemobile2' => $order->employee->mobile2 ?? null,
                    'agence' => $order->employee->agence->agent_name ?? null,
                    'orderItems' => $order->orderItems->map(function ($item) {
                        return [
                            'orderItemsId' => $item->id,
                            'orderItemsStatus' => $item->status,
                            'quantity' => $item->quantity,
                            'productname' => $item->product->product_name ?? null,
                            'productprice' => $item->product->price ?? null,
                            'product_images1' => $item->product->product_images1 ?? null,
                            'product_images2' => $item->product->product_images2 ?? null,
                            'product_images3' => $item->product->product_images3 ?? null,
                        ];
                    }),
                ];
            });

            return $formattedOrders;
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return back()->with('error', 'An error occurred' . $e->getMessage());
        }
    }





    public function NewvendorvalidateOrder(Request $request)
    {
        try {
            // Récupérer le vendeur authentifié
            $vendor = Auth::user();

            // Vérifier si l'utilisateur est un vendor
            if (!$vendor) {
                return back()->with('error', 'Unauthorized access');
            }

            // Récupérer la commande par son ID
            $order = Order::find($request->orderid);

            // Vérifier si la commande existe
            if (!$order) {
                return back()->with('error', 'Order not found.');
            }

            // Récupérer les éléments de la commande associés au vendor
            $orderItems = order_items::where('vendor_id', $vendor->id)
                ->where('order_id', $order->id)
                ->get();

            // Vérifier si des éléments de commande ont été trouvés
            if ($orderItems->isEmpty()) {
                return back()->with('error', 'No order items found for this seller and this order.');
            }


            foreach ($orderItems as $orderItem) {
                if ($orderItem->status == 2) {
                    return back()->with('error', 'Order item is already processing.');
                }

                $orderItem->status = 2;  // Mettre à jour le statut
                $orderItem->save();
            }


            $allOrderItems = order_items::where('order_id', $order->id)->get();

            // Vérifier si tous les orderItems ont le statut 2
            $allOrderItemsHaveStatus2 = $allOrderItems->every(function ($orderItem) {
                return $orderItem->status == 2;
            });

            // Si tous les orderItems ont le statut 2, mettre à jour le statut de la commande
            if ($allOrderItemsHaveStatus2) {
                $order->status = 2;
                $order->save();
            }

            return back()->with('success', 'Order successfully processing.');
        } catch (\Exception $e) {
            // Log des erreurs et gestion
            Log::error('An error occurred: ' . $e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // public function NewAdminVendorValidateOrder(Request $request)
    // {
    //     try {


    //         // Récupérer le vendeur authentifié
    //         $vendor = Auth::user();

    //         if (!$vendor) {
    //             return back()->with('error', 'Unauthorized access');
    //         }

    //         $order = Order::find($request->orderid);


    //         if (!$order) {
    //             return back()->with('error', 'Order not found.');
    //         }

    //         if ($order->status == 3) {
    //             return back()->with('error', 'The order has already been validated');
    //         }

    //         $allOrderItems = order_items::where('order_id', $order->id)->get();

    //         $allOrderItemsHaveStatus2 = $allOrderItems->every(function ($orderItem) {
    //             return $orderItem->status == 2;
    //         });

    //         if (!$allOrderItemsHaveStatus2) {
    //             return back()->with('error', 'Validation failed because not all vendors have validated their products.');
    //         }

    //         $order->status = 3;
    //         $order->save();

    //         foreach ($allOrderItems as $item) {
    //             $item->status = 3;
    //             $item->save();
    //         }

    //         return back()->with('success', 'Order successfully processing.');
    //     } catch (\Exception $e) {
    //         Log::error('An error occurred: ' . $e->getMessage());
    //         return back()->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }



    public function NewAdminVendorValidateOrder(Request $request)
    {
        try {
            // Récupérer le vendeur authentifié
            $vendor = Auth::user();

            if (!$vendor) {
                return back()->with('error', 'Unauthorized access');
            }

            // Récupérer la commande par son ID
            $order = Order::find($request->orderid);

            if (!$order) {
                return back()->with('error', 'Order not found.');
            }

            // if ($order->status == 3) {
            //     return back()->with('error', 'The order has already been validated');
            // }

            // Récupérer tous les order_items de la commande
            $allOrderItems = order_items::where('order_id', $order->id)->get();

            if ($allOrderItems->isEmpty()) {
                return back()->with('error', 'No items found for this order.');
            }

            $itemsWithStatusOne = $allOrderItems->filter(function ($item) {
                return $item->status == 1;
            });

            // Mettre à jour les items avec status = 2 à status = 3
            $itemsWithStatusTwo = $allOrderItems->filter(function ($item) {
                return $item->status == 2;
            });

            foreach ($itemsWithStatusTwo as $item) {
                $item->status = 3;
                $item->save();
            }

            // Vérifier s'il reste des items avec status = 2
            $remainingItemsWithStatusTwo = $allOrderItems->filter(function ($item) {
                return $item->status == 2;
            });

            // Mettre à jour la commande si tous les items sont validés (plus de status = 2)
            if ($remainingItemsWithStatusTwo->isEmpty()) {
                $order->status = 3;
                $order->save();
            }

            // Préparer les messages en fonction des résultats
            if ($itemsWithStatusOne->isNotEmpty()) {
                return back()->with('success', 'Some items with status 1 cannot be validated, but others were successfully processed.');
            }

            return back()->with('success', 'Order items successfully validated, and order status updated if applicable.');
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }







    public function getvendoruniqueorderproduit()
    {
        try {
            $vendor = Auth::user();
            if (!$vendor) {
                return back()->with('error', 'Unauthorized access');
            }
            $orderItems = order_items::where('vendor_id', $vendor->id)
                ->with(['product', 'order.employee'])

                ->orderby('id', 'desc')
                ->get();

            $mappedOrderItems = $orderItems->map(function ($item) {

                return [
                    'orderitemid' => $item->id,
                    'orderitemorderid' => $item->order_id,
                    'orderitemproductid' => $item->product_id,
                    'orderitemvendorid' => $item->vendor_id,
                    'orderitemquantity' => $item->quantity,
                    'orderitemtotal' => $item->total,
                    'orderitemstatus' => $item->status,
                    'orderitemcreated_at' => $item->created_at,
                    'updated_at' => $item->updated_at,

                    //product
                    'productid' => $item->product->id ?? null,
                    'productproductname' => $item->product->product_name ?? null,
                    'productdescription' => $item->product->product_description ?? null,
                    'productstock' => $item->product->stock ?? null,
                    'productprice' => $item->product->price ?? null,
                    'productstatus' => $item->product->status ?? null,
                    'productimages1' => $item->product->product_images1 ?? null,
                    'productimages2' => $item->product->product_images2 ?? null,
                    'productimages3' => $item->product->product_images3 ?? null,

                    //order
                    'orderid' => $item->order->id ?? null,
                    'ordertotal' => $item->order->total ?? null,
                    'orderstatus' => $item->order->status ?? null,
                    'ordercreated_at' => $item->order->created_at ?? null,

                    //employye

                    'employeeid' => $item->order->employee->id ?? null,
                    'employeenationalid' => $item->order->employee->national_id ?? null,
                    'employeefirstname' => $item->order->employee->firstname ?? null,
                    'employeelastname' => $item->order->employee->lastname ?? null,
                    'employeeusername' => $item->order->employee->username ?? null,
                    'employeemiddlename' => $item->order->employee->middle_name ?? null,
                    'employeeemail' => $item->order->employee->email ?? null,
                    'employeemobile' => $item->order->employee->mobile ?? null,
                    'employeemobile2' => $item->order->employee->mobile2 ?? null,
                    'employeeavatar' => $item->order->employee->avatar ?? null,
                    'employeestatus' => $item->order->employee->status ?? null,
                    'employeeagencescode' => $item->order->employee->agencescode ?? null,




                ];
            });

            return $mappedOrderItems;
        } catch (\Exception $e) {
            Log::info('An occured error' . $e->getMessage());
            return back()->with('error', 'An occured error');
        }
    }

    public function addOrderCodeToOrder()
    {
        try {
            // Récupère tous les ordres ayant un code de commande défini
            $orders = Order::whereNotNull('ordercode')->get();

            foreach ($orders as $order) {

                // Génère un nouveau code de commande et l'attribue à l'ordre
                $order->ordercode = $this->generateOrderCode();
                $order->save(); // Sauvegarde la commande en base de données

            }

            // Si aucune exception n'est levée pour toutes les commandes, retourne une réponse de succès générale
            return response()->json(['success' => true, 'message' => 'All order codes updated successfully.']);
        } catch (\Exception $e) {
            // Gestion des erreurs génériques lors de la récupération des commandes
            return response()->json(['success' => false, 'message' => 'Failed to update order codes.'], 500);
        }
    }

    public function admingetvendororder()
    {
        try {
            $vendor = Auth::user();

            // Vérifier si l'utilisateur est un vendor
            if (!$vendor) {
                return back()->with('error', 'Unauthorized access');
            }

            $orders = Order::with(['orderItems.product', 'employee.agence'])
                ->orderBy('id', 'desc')
                ->get();

            $formattedOrders = $orders->map(function ($order) {
                return [
                    "orderId" => $order->id,
                    'orderTotal' => $order->total,
                    'ordercode' => $order->ordercode,
                    'orderStatus' => $order->status,
                    'ordercreated' => $order->created_at,
                    'employeefirstname' => $order->employee->firstname ?? null,
                    'employeelastname' => $order->employee->lastname ?? null,
                    'employeeusername' => $order->employee->username ?? null,
                    'employeemiddlename' => $order->employee->middle_name ?? null,
                    'employeeemail' => $order->employee->email ?? null,
                    'employeemobile' => $order->employee->mobile ?? null,
                    'employeemobile2' => $order->employee->mobile2 ?? null,
                    'agence' => $order->employee->agence->agent_name ?? null,
                    'orderItems' => $order->orderItems->map(function ($item) {
                        return [
                            'orderItemsId' => $item->id,
                            'orderItemsStatus' => $item->status,
                            'quantity' => $item->quantity,
                            'productname' => $item->product->product_name ?? null,
                            'productprice' => $item->product->price ?? null,
                            'product_images1' => $item->product->product_images1 ?? null,
                            'product_images2' => $item->product->product_images2 ?? null,
                            'product_images3' => $item->product->product_images3 ?? null,
                        ];
                    }),
                ];
            });

            return $formattedOrders;
        } catch (\Exception $e) {
            Log::info('An occured error' . $e->getMessage());
            return back()->with('error', 'An occured error');
        }
    }

    public function searchOrder(Request $request)
    {
        try {
            // Récupérer le terme de recherche
            $searchTerm = $request->input('search');

            // Construire la requête avec un filtre dynamique
            $orders = Order::with(['orderItems.product', 'employee.agence'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    $query->where(function ($query) use ($searchTerm) {
                        $query->whereHas('orderItems.product', function ($q) use ($searchTerm) {
                            $q->where('product_name', 'like', '%' . $searchTerm . '%');
                        })
                            ->orWhereHas('employee', function ($q) use ($searchTerm) {
                                $q->where('mobile', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('mobile2', 'like', '%' . $searchTerm . '%');
                            })
                            ->orWhere('ordercode', 'like', '%' . $searchTerm . '%');
                    });
                })
                ->orderBy('id', 'desc')
                ->get();

            // Formatter les données
            $formattedOrders = $orders->map(function ($order) {
                return [
                    "orderId" => $order->id,
                    'orderTotal' => $order->total,
                    'ordercode' => $order->ordercode,
                    'orderStatus' => $order->status,
                    'orderCreated' => $order->created_at,
                    'employeefirstname' => $order->employee->firstname ?? null,
                    'employeelastname' => $order->employee->lastname ?? null,
                    'employeeusername' => $order->employee->username ?? null,
                    'employeemiddlename' => $order->employee->middle_name ?? null,
                    'employeeemail' => $order->employee->email ?? null,
                    'employeemobile' => $order->employee->mobile ?? null,
                    'employeemobile2' => $order->employee->mobile2 ?? null,
                    'agence' => $order->employee->agence->agent_name ?? null,
                    'orderItems' => $order->orderItems->map(function ($item) {
                        return [
                            'orderItemsId' => $item->id,
                            'orderItemsStatus' => $item->status,
                            'quantity' => $item->quantity,
                            'productname' => $item->product->product_name ?? null,
                            'productprice' => $item->product->price ?? null,
                            'product_images1' => $item->product->product_images1 ?? null,
                            'product_images2' => $item->product->product_images2 ?? null,
                            'product_images3' => $item->product->product_images3 ?? null,
                        ];
                    }),
                ];
            });

            // Retourner les résultats formatés
            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'orders' => $formattedOrders
            ], 200);
        } catch (\Exception $e) {
            // Log de l'erreur
            Log::error('An error occurred: ' . $e->getMessage());

            // Retourner une réponse JSON en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function SearchgetOrders(Request $request)
{
    try {
        // Récupérer l'utilisateur connecté (vendeur)
        $vendor = Auth::user();
        if (!$vendor) {
            return back()->with('error', 'Unauthorized access');
        }

        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->input('search');

        // Récupérer les IDs des commandes associées au vendeur
        $orderIds = order_items::where('vendor_id', $vendor->id)->pluck('order_id');

        // Construire la requête pour récupérer les commandes
        $query = Order::with([
            'orderItems' => function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            },
            'orderItems.product',
            'employee.agence'
        ])
            ->whereIn('id', $orderIds)
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->whereHas('orderItems.product', function ($q) use ($searchTerm) {
                        $q->where('product_name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('employee', function ($q) use ($searchTerm) {
                        $q->where('mobile', 'like', '%' . $searchTerm . '%')
                          ->orWhere('mobile2', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('ordercode', 'like', '%' . $searchTerm . '%');
                });
            });

        // Récupérer et trier les commandes
        $orders = $query->orderBy('id', 'desc')->get();

        // Formater les commandes
        $formattedOrders = $orders->map(function ($order) {
            return [
                "orderId" => $order->id,
                'orderTotal' => $order->total,
                'ordercode' => $order->ordercode,
                'orderStatus' => $order->status,
                'ordercreated' => $order->created_at,
                'employeefirstname' => $order->employee->firstname ?? null,
                'employeelastname' => $order->employee->lastname ?? null,
                'employeeusername' => $order->employee->username ?? null,
                'employeemiddlename' => $order->employee->middle_name ?? null,
                'employeeemail' => $order->employee->email ?? null,
                'employeemobile' => $order->employee->mobile ?? null,
                'employeemobile2' => $order->employee->mobile2 ?? null,
                'agence' => $order->employee->agence->agent_name ?? null,
                'orderItems' => $order->orderItems->map(function ($item) {
                    return [
                        'orderItemsId' => $item->id,
                        'orderItemsStatus' => $item->status,
                        'quantity' => $item->quantity,
                        'productname' => $item->product->product_name ?? null,
                        'productprice' => $item->product->price ?? null,
                        'product_images1' => $item->product->product_images1 ?? null,
                        'product_images2' => $item->product->product_images2 ?? null,
                        'product_images3' => $item->product->product_images3 ?? null,
                    ];
                }),
            ];
        });

        return response()->json(['orders'=>$formattedOrders]);
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return back()->with('error', 'An error occurred while processing your request. Please try again.');
    }
}

}
