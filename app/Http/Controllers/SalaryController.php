<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\order;
use App\Models\payementsalaires;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function assignMinimumSalary()
    {
        $minimumSalary = 300000;

        $users = employee::all();

        foreach ($users as $user) {

                Payementsalaires::create([
                    'user_id' => $user->id,
                    'amount' => $minimumSalary,
                    'status' => 3
                ]);

        }

        // Retourner une réponse JSON après l'opération
        return response()->json([
            'message' => 'Salaire minimum de 250000 assigné à tous les utilisateurs.',
        ], 200);
    }


    // public function Rhvalidatedorder(Request $request) {
    //     try {
    //         // Récupérer la dernière commande validée pour un utilisateur donné
    //         $order = Order::where('user_id', 1)
    //             ->where('status', 2)
    //             ->latest()
    //             ->first();

    //         // Vérifier si la commande existe
    //         if (!$order) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Aucune commande validée trouvée.',
    //             ], 404);
    //         }

    //         // Récupérer les salaires des trois derniers mois
    //         $salaries = Payementsalaires::where('user_id', $order->user_id)
    //         ->where('created_at', '>=', now()->subMonths(3))
    //         ->where('created_at', '<=', now())
    //         ->orderBy('created_at', 'desc')
    //         ->take(3)
    //         ->pluck('amount');

    //         // Vérifier s'il y a au moins 3 salaires
    //         if ($salaries->count() < 3) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'L\'utilisateur doit avoir au moins 3 salaires pour valider la commande.',
    //             ], 400);
    //         }

    //         // Calculer la somme des salaires
    //         $totalSalaries = $salaries->sum();

    //         // Calculer la moyenne des salaires sur 3 mois
    //         $averageSalary = $totalSalaries / 3;
    //         $newamountsalarie=$averageSalary/3;

    //         // Vérifier si la moyenne des salaires est supérieure ou égale au montant de la commande
    //         if ($newamountsalarie < $order->total) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'La moyenne des salaires est inférieure au montant de la commande.',
    //             ], 400);
    //         }

    //         $order->status=3;
    //         $order->save();
    //         // Retourner la commande trouvée
    //         return response()->json([
    //             'success' => true,
    //             'data' => $order,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur de validation de l\'ordre',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (\Throwable $t) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur de validation de l\'ordre',
    //             'error' => $t->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function Rhvalidatedorder(Request $request) {
    //     try {
    //         // Retrieve the last validated order for a given user
    //         $order = Order::where('user_id', $request->userid)
    //             // ->where('status', 2)
    //             ->latest()
    //             ->first();
    
    //         // Check if the order exists
    //         if (!$order) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No validated order found.',
    //             ], 404);
    //         }
    //         if($order->status==3){
    //             return response()->json([
    //                 'success'=>false,
    //                 'message'=>"order is already validated"
    //             ],403);
    //         }
    
    //         // Retrieve salaries from the last three months
    //         $salaries = Payementsalaires::where('user_id', $order->user_id)
    //             ->where('created_at', '>=', now()->subMonths(3))
    //             ->where('created_at', '<=', now())
    //             ->orderBy('created_at', 'desc')
    //             ->take(3)
    //             ->pluck('amount');
    
    //         // Check if there are at least 3 salaries
    //         if ($salaries->count() < 3) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'The user must have at least 3 salaries to validate the order.',
    //             ], 400);
    //         }
    
    //         // Calculate the sum of the salaries
    //         $totalSalaries = $salaries->sum();
    
    //         // Calculate the average salary over 3 months
    //         $averageSalary = $totalSalaries / 3;
    //         $newAmountSalary = $averageSalary / 3;
    
    //         // Check if the average salary is greater than or equal to the order amount
    //         if ($newAmountSalary < $order->total) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Your salary does not allow you to exceed the order amount.',
    //             ], 400);
    //         }
    
    //         $order->status = 3;
    //         $order->save();
            
    //         // Return the found order
    //         return response()->json([
    //             'success' => true,
    //             'data' => $order,
    //             'message'=>'the order is validated'
    //         ], 200);
    
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order validation error.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (\Throwable $t) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Order validation error.',
    //             'error' => $t->getMessage(),
    //         ], 500);
    //     }
    // }
    

    public function Rhvalidatedorder(Request $request) {
        try {
            // Retrieve the last validated order for the given user
            $order = Order::where('user_id', $request->userid)
                // ->where('status', 2) // Uncomment if needed to filter orders by status
                ->latest()
                ->first();
    
            // Check if the order exists
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'No validated order found.',
                ], 404);
            }
    
            // Check if the order is already validated
            if ($order->status == 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is already validated.',
                ], 403);
            }
    
            // Retrieve salaries from the last three months
            $salaries = Payementsalaires::where('user_id', $order->user_id)
                ->whereBetween('created_at', [now()->subMonths(3), now()])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->pluck('amount');
    
            // Check if there are at least 3 salaries
            if ($salaries->count() < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'The user must have at least 3 salaries to validate the order.',
                ], 400);
            }
    
            // Calculate the average salary over the last 3 months
            $averageSalary = $salaries->sum() / 3;
            $newAmountSalary = $averageSalary / 3;
    
            // Check if the average salary allows the user to exceed the order amount
            if ($newAmountSalary < $order->total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your salary does not allow you to exceed the order amount.',
                ], 400);
            }
    
            // Update the order status to validated
            $order->status = 3;
            $order->save();
    
            // Return the validated order
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'The order is validated.',
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order validation error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function getAllOrders(Request $request) {
        try {
            // Récupérer toutes les commandes avec les employés associés
            $orders = Order::where('status',2)
           ->with('employee')->get();

            // Vérifier si des commandes existent
            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune commande trouvée.',
                ], 404); // 404 Not Found
            }

            // Formater les données
            $formattedOrders = $orders->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id, // Assurez-vous que c'est bien le bon ID
                    'total' => $order->total,
                    'order_status' => $order->status,
                    'employee_id'=>$order->employee->id,
                    'firstname'=>$order->employee->firstname,
                    'lastname'=>$order->employee->lastname,
                    'username' => $order->employee->username,
                    'email'=>$order->employee->email,
                    'mobile'=>$order->employee->phone,
                    'avatar'=>$order->employee->avatar,
                    'created_at' => $order->created_at,

                ];
            });

            // Retourner les commandes formatées
            return response()->json([
                'success' => true,
                'data' => $formattedOrders,
            ], 200); // 200 OK

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des commandes',
                'error' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        } catch (\Throwable $t) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des commandes',
                'error' => $t->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }



}
