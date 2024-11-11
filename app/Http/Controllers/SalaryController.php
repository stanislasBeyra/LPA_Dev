<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\order;
use App\Models\payementperiodemode;
use App\Models\payementsalaires;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function assignMinimumSalary()
    {
        $minimumSalary = 300000000;

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


    


    public function Rhvalidatedorder(Request $request)
    {
        try {
            // Retrieve the last validated order for the given user
            $order = Order::where('user_id', $request->userid)
                ->where('id', $request->orderId)
                ->where('status', 2) // Uncomment if needed to filter orders by status
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
                    'message' => "The customer's salary does not allow you to exceed the order amount.",
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


    public function getAllOrders(Request $request)
    {
        try {
            // Récupérer toutes les commandes avec les employés associés
            $orders = Order::where('status', 2)
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
                    'employee_id' => $order->employee->id,
                    'firstname' => $order->employee->firstname,
                    'lastname' => $order->employee->lastname,
                    'username' => $order->employee->username,
                    'email' => $order->employee->email,
                    'mobile' => $order->employee->phone,
                    'avatar' => $order->employee->avatar,
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

    //     public function getCutomerPaiement()
    // {
    //     try {
    //         // Récupérer les paiements avec les utilisateurs associés
    //         $paies = payementperiodemode::with('user')->get();

    //         return response()->json([
    //             "data" => $paies
    //         ], 200);

    //     } catch (\Throwable $t) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la récupération des commandes',
    //             'error' => $t->getMessage(),
    //             'line' => $t->getLine()
    //         ], 500);
    //     }
    // }

    public function getCutomerPaiement()
    {
        try {
            // Récupérer les paiements avec les utilisateurs associés
            $paies = payementperiodemode::with('paiementusers')
            ->orderby('id','desc')
            ->get();

            // Formater chaque paiement avec les données de l'utilisateur
            $formattedPaies = $paies->map(function ($paie) {
                return [
                    "id" => $paie->id,
                    "user_id" => $paie->user_id,
                    "order_id" => $paie->order_id,
                    "total_amount" => $paie->total_amount,
                    "period" => $paie->period,
                    "month_1" => $paie->month_1,
                    "month_2" => $paie->month_2,
                    "month_3" => $paie->month_3,
                    "month_4" => $paie->month_4,
                    "month_5" => $paie->month_5,
                    "month_6" => $paie->month_6,
                    "user_firstname" => $paie->user->firstname??null,
                    "user_lastname" => $paie->user->lastname??null,
                    "user_username" => $paie->user->username??null,
                    "user_email" => $paie->user->email??null,
                    "user_mobile" => $paie->user->mobile??null,
                    "user_status" => $paie->user->status??null,
                    "user_net_salary" => $paie->user->net_salary??0,
                    "created_at" => $paie->created_at,

                ];
            });

            return response()->json([
                "paiementdata" => $formattedPaies
            ], 200);
        } catch (\Throwable $t) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des commandes',
                'error' => $t->getMessage(),
                'line' => $t->getLine()
            ], 500);
        }
    }
}
