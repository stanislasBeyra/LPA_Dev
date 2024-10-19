<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\order_items;
use App\Models\payementperiodemode;
use App\Models\Product;
use App\Models\productcategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{


    private function base64ToFile($base64String, $folder, $filename)
    {
        // Supprimer le préfixe de la chaîne Base64
        $base64String = preg_replace('/^data:image\/(jpg|jpeg|png);base64,/', '', $base64String);
        $base64String = str_replace(' ', '+', $base64String);
        $fileData = base64_decode($base64String);

        // Créer un chemin de fichier dans le répertoire de stockage
        $filePath = public_path('app/public/' . $folder . '/' . $filename);

        // Créer le dossier si nécessaire
        $directory = dirname($filePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Sauvegarder le fichier
        file_put_contents($filePath, $fileData);

        return $filename;
    }




    public function AddVendorProduct(Request $request)
    {
        try {
            $uservendor = Auth::user();

            // Validation des données
            $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_description' => 'required|string',
                'stock' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'categorie_id' => 'required|integer',
                'product_images1' => 'required|string',
                'product_images2' => 'nullable|string',
                'product_images3' => 'nullable|string',
            ]);

            // Log de l'ajout du produit
            Log::info('Adding product', [
                'user_id' => $uservendor->id,
                'product_name' => $validated['product_name'],
                'categorie_id' => $validated['categorie_id'],
                'product_images1' => $validated['product_images1'],
            ]);

            // Convertir les images Base64 en fichiers et les stocker dans le répertoire public
            $image1Filename = $this->base64ToFile($validated['product_images1'], 'product_images', uniqid() . '.png');
            $image2Filename = $validated['product_images2'] ? $this->base64ToFile($validated['product_images2'], 'product_images', uniqid() . '.png') : null;
            $image3Filename = $validated['product_images3'] ? $this->base64ToFile($validated['product_images3'], 'product_images', uniqid() . '.png') : null;

            // Les chemins relatifs aux fichiers stockés
            $image1Path = 'product_images/' . $image1Filename;
            $image2Path = $image2Filename ? 'product_images/' . $image2Filename : null;
            $image3Path = $image3Filename ? 'product_images/' . $image3Filename : null;

            // Création du produit
            $product = Product::create([
                'product_name' => $validated['product_name'],
                'product_description' => $validated['product_description'],
                'stock' => $validated['stock'],
                'price' => $validated['price'],
                'vendor_id' => $uservendor->id,
                'categorie_id' => $validated['categorie_id'],
                'product_images1' => $image1Path,
                'product_images2' => $image2Path,
                'product_images3' => $image3Path,
            ]);

            return response()->json(['success' => 'Product added successfully', 'product' => $product], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception occurred', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateVendorProduct(Request $request, $productId)
    {
        try {
            $uservendor = Auth::user();

            // Vérification du rôle
            if ($uservendor->role != 3) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }

            // Recherche du produit
            $product = Product::where('vendor_id', $uservendor->id)->findOrFail($productId);

            // Validation des données
            $validated = $request->validate([
                'product_name' => 'sometimes|string|max:255',
                'product_description' => 'sometimes|string',
                'stock' => 'sometimes|integer|min:1',
                'price' => 'sometimes|numeric|min:0',
                'categorie_id' => 'sometimes|integer',
            ]);

            // Log de la mise à jour du produit
            Log::info('Updating product', [
                'validated_data' => $validated,
                'user_id' => $uservendor->id,
                'product_id' => $productId,
                'product_name' => $validated['product_name'] ?? $product->product_name,
                'categorie_id' => $validated['categorie_id'] ?? $product->categorie_id,
            ]);

            // Mise à jour des champs du produit sans gérer les images
            $product->update([
                'product_name' => $validated['product_name'] ?? $product->product_name,
                'product_description' => $validated['product_description'] ?? $product->product_description,
                'stock' => $validated['stock'] ?? $product->stock,
                'price' => $validated['price'] ?? $product->price,
                'categorie_id' => $validated['categorie_id'] ?? $product->categorie_id,
            ]);

            return response()->json(['success' => true, 'message' => 'Product updated successfully', 'product' => $product], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception occurred', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function getVendorProducts()
    // {
    //     try {
    //         // Retrieve the currently authenticated user
    //         $uservendor = Auth::user();

    //         if (!$uservendor) {
    //             // Return a JSON response if the vendor is not found
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Your are not authenticated.'
    //             ], 404);
    //         }
    //         // Retrieve products associated with the vendor
    //         $products = Product::where('vendor_id', $uservendor->id)
    //             ->latest()
    //             ->take(10)
    //             ->get();
    //         // Return a JSON response with the products
    //         return response()->json([
    //             'success' => true,
    //             'products' => $products
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Handle exceptions and return a JSON error response
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function getVendorProducts()
    {
        try {
            // Récupérer l'utilisateur authentifié
            $uservendor = Auth::user();

            if (!$uservendor) {
                // Retourner une réponse JSON si le vendeur n'est pas trouvé
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.'
                ], 404);
            }

            // Récupérer les produits associés au vendeur et leurs catégories
            $products = Product::with('category') // Chargement de la relation
                ->where('vendor_id', $uservendor->id)
                ->latest()
                ->take(10)
                ->get();

            // Mapper les produits pour inclure le nom de la catégorie
            $responseProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_description' => $product->product_description,
                    'stock' => $product->stock,
                    'price' => $product->price,
                    'vendor_id' => $product->vendor_id,
                    'categorie_id' => $product->categorie_id,
                    'status' => $product->status,
                    'product_images1' => $product->product_images1,
                    'product_images2' => $product->product_images2,
                    'product_images3' => $product->product_images3,
                    'deleted_at' => $product->deleted_at,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                    'categories_name' => $product->category->categories_name, // Ajout du nom de la catégorie
                ];
            });

            // Retourner une réponse JSON avec les produits et leurs catégories
            return response()->json([
                'success' => true,
                'products' => $responseProducts
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


    public function showPductsDetails($id)
    {
        try {


            $uservendor = Auth::user();

            if (!$uservendor) {
                // Return a JSON response if the vendor is not found
                return response()->json([
                    'success' => false,
                    'message' => 'Your are not authenticated.'
                ], 404);
            }
            $product = Product::where('vendor_id', $uservendor->id)
                ->where('id', $id)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }


            return response()->json([
                'success' => true,
                'products' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyProduct($id)
    {
        try {
            // Récupérer l'utilisateur actuellement authentifié
            $uservendor = Auth::user();

            if (!$uservendor) {
                // Retourner une réponse JSON si l'utilisateur n'est pas authentifié
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.'
                ], 404);
            }

            // Trouver le produit correspondant à l'ID et appartenant au vendeur authentifié
            $product = Product::where('id', $id)
                ->where('vendor_id', $uservendor->id)
                ->first();

            if (!$product) {
                // Retourner une réponse JSON si le produit n'est pas trouvé
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }

            Log::info('delete info', [
                "delete" => $product

            ]);

            // Supprimer le produit
            $product->delete();

            // Retourner une réponse JSON après suppression réussie
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            // Gérer les exceptions et retourner une réponse JSON avec l'erreur
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addCategory(Request $request)
{
    try {
        // Vérifie si l'utilisateur est authentifié
        $userVendor = Auth::user();
        if (!$userVendor) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated.'
            ], 401); // Code de statut 401 pour une authentification requise
        }

        // Validation des données de la requête
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Le nom de la catégorie est requis
            'description' => 'nullable|string|max:500', // La description est optionnelle
        ]);

        // Vérification de l'existence de la catégorie en ignorant la casse
        $category = ProductCategories::whereRaw('LOWER(categories_name) = ?', [strtolower($validatedData['name'])])
            ->first();

        if ($category) {
            return response()->json([
                'success' => false,
                'message' => 'Category already exists.'
            ], 409);  // Conflit HTTP
        }

        // Création de la catégorie avec les données validées
        $category = new ProductCategories();
        $category->categories_name = $validatedData['name'];
        $category->categories_description = $validatedData['description'] ?? null; // Ajouter la description si fournie
        $category->save();

        // Retourner une réponse JSON après la création réussie
        return response()->json([
            'success' => true,
            'message' => 'Category added successfully.',
            'category' => $category
        ], 201); // Code de statut 201 pour une création réussie
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Gestion des erreurs de validation
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors()
        ], 422); // Code de statut 422 pour une erreur de validation
    } catch (\Exception $e) {
        // Gestion des autres exceptions
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ], 500); // Code de statut 500 pour une erreur interne du serveur
    }
}

public function getCategory()
{
    try {
        $UserVendor = Auth::user();
        if (!$UserVendor) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated.'
            ], 401); // Changement du code d'état à 401
        }

        // Récupération des catégories actives
        $categories = productcategories::where('status', 1)
        ->orderBy('categories_name','asc')
        ->get();

        // Vérification si des catégories sont trouvées
        if ($categories->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No categories found.'
            ], 404);
        }

        // Préparation de la réponse
        $response = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->categories_name,
                'description' => $category->categories_description,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'categories' => $response // Renvoie toutes les catégories
        ], 200);
    } catch (\Exception $e) {
        // Gestion des autres exceptions
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function destroyCategory($id)
    {
        try {
            $UserVendor = Auth::user();
            if (!$UserVendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.'
                ], 404);
            }
            $category = productcategories::find($id);
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found.'
                ], 404);
            }
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            // Gestion des erreurs générales
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            // Vérification de l'authentification de l'utilisateur
            $UserVendor = Auth::user();
            if (!$UserVendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.'
                ], 404);
            }

            // Validation des données de la requête
            $validatedData = $request->validate([
                'categories_name' => 'required|string|max:255',
            ]);

            // Récupérer la catégorie par ID
            $category = productcategories::find($id);

            // Vérifier si la catégorie existe
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found.'
                ], 404);
            }

            // Vérifier si une catégorie avec le même nom existe déjà (insensible à la casse)
            $existingCategory = productcategories::where('categories_name', 'LIKE', $validatedData['categories_name'])
                ->where('id', '!=', $id)
                ->first();
            if ($existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'A category with this name already exists.'
                ], 409);
            }

            // Mise à jour des informations de la catégorie
            $category->categories_name = $validatedData['categories_name'];
            $category->save();

            // Retourner une réponse JSON après la mise à jour réussie
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => $category
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

    public function deleteAllProducts()
    {
        try {
            // Assurez-vous que l'utilisateur est authentifié
            $user = Auth::user();

            // Vérifiez que l'utilisateur est authentifié
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }

            // Supprimez tous les produits de l'utilisateur
            $deletedCount = Product::where('vendor_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' products have been deleted.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Exception occurred', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getVendorOrders()
    {
        try {
            // Récupérer l'utilisateur authentifié
            $uservendor = Auth::user();

            // Vérifier si l'utilisateur est bien un vendeur
            if (!$uservendor) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Récupérer les produits du vendeur
            $products = Product::where('vendor_id', $uservendor->id)->pluck('id');

            // Récupérer les commandes associées aux produits du vendeur
            $orders = Order::whereHas('orderItems', function ($query) use ($products) {
                $query->whereIn('product_id', $products);
            })->with(['orderItems.product', 'user'])
                ->orderByDesc('id')
                ->get();
            // Parcourir chaque commande pour ajouter les produits associés
            $orderDetails = $orders->map(function ($order) {
                // Vérifier si l'utilisateur est associé à la commande
                $user = $order->employee;
                if (!$user) {
                    $userDetails = ['id' => null, 'name' => 'Unknown', 'email' => 'Unknown'];
                } else {
                    $userDetails = [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                    ];
                }

                // Parcourir les produits de la commande
                $products = $order->orderItems->map(function ($item) {
                    // Vérifier si le produit est associé à l'item
                    $product = $item->product;
                    if (!$product) {
                        return [
                            'product_id' => null,
                            'product_name' => 'Unknown',
                            'quantity' => $item->quantity,
                            'price' => $item->total,
                        ];
                    }
                    return [
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->total,
                        "product_images1" => $item->product->product_images1,
                        "product_images2" => $item->product->product_images1,
                        "product_images3" => $item->product->product_images3,
                    ];
                });

                return [
                    'order_id' => $order->id,
                    'username' => $userDetails['username'],
                    'useremail' => $userDetails['email'],
                    'total_price' => $order->total,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'products' => $products
                ];
            });

            // Retourner les détails des commandes avec les produits
            return response()->json(['success' => true, 'orders' => $orderDetails], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching vendor orders', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function VendorvalidateOrder(Request $request)
    {
        try {
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();

            // Vérifiez que l'utilisateur est authentifié
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }
            // Récupérer la commande par son ID
            $order = Order::where('id', $request->orderId)->firstOrFail();
            // Vérifiez si la commande est trouvée
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            // Récupérer les éléments de la commande
            $orderItems = order_items::where('order_id', $order->id)->get();

            // Vérifiez si la commande est vide
            if ($orderItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is empty.',
                ], 404);
            }

            // Mettre à jour le statut de la commande pour la valider
            // 2 = validé par le fournisseur
            $order->status = 2;
            $order->save();

            // Mettre à jour le statut des éléments de la commande
            foreach ($orderItems as $item) {
                $item->status = 2;
                $item->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Order validated successfully.',
                'order' => $order, // Retourner la commande mise à jour si nécessaire
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





    //
//     public function payement(Request $request)
// {
//     // Validation des données
//     $validatedData = $request->validate([
//         'total_amount' => 'required|numeric|min:0',
//         'period' => 'required|integer|min:0|max:6', // La période peut être de 0 à 6
//         'month_1' => 'nullable|numeric|min:0',
//         'month_2' => 'nullable|numeric|min:0',
//         'month_3' => 'nullable|numeric|min:0',
//         'month_4' => 'nullable|numeric|min:0',
//         'month_5' => 'nullable|numeric|min:0',
//         'month_6' => 'nullable|numeric|min:0',
//     ]);

//     // Si la période est 0, définissons la période à 6 par défaut
//     $period = $validatedData['period'] === 0 ? 6 : $validatedData['period'];

//     // Créer un paiement
//     $payment = new payementperiodemode();
//     $payment->user_id = 1; // Utilisateur connecté
//     $payment->total_amount = $validatedData['total_amount'];
//     $payment->period = $period;

//     // Répartition des montants
//     if ($period === 0 || (empty($validatedData['month_1']) &&
//         empty($validatedData['month_2']) &&
//         empty($validatedData['month_3']) &&
//         empty($validatedData['month_4']) &&
//         empty($validatedData['month_5']) &&
//         empty($validatedData['month_6']))) {

//         // Utilisation de la méthode pour répartir le montant
//         $payment->distributeAmountOverMonths($validatedData['total_amount'], $period);
//     } else {
//         // Affectation des montants saisis par l'utilisateur
//         $payment->month_1 = $validatedData['month_1'] ?? 0; // Prendre la valeur ou 0 si vide
//         if ($period >= 2) $payment->month_2 = $validatedData['month_2'] ?? 0;
//         if ($period >= 3) $payment->month_3 = $validatedData['month_3'] ?? 0;
//         if ($period >= 4) $payment->month_4 = $validatedData['month_4'] ?? 0;
//         if ($period >= 5) $payment->month_5 = $validatedData['month_5'] ?? 0;
//         if ($period == 6) $payment->month_6 = $validatedData['month_6'] ?? 0;
//     }

//     $totalMonthsAmount = $payment->month_1 + $payment->month_2 + $payment->month_3 + $payment->month_4 + $payment->month_5 + $payment->month_6;

//     if ($totalMonthsAmount < $payment->total_amount) {
//         return response()->json([
//             'message' => 'La somme est inferieur au montant dachat.Veuillez bien renseignez les champ.',
//             'data' => $payment
//         ], 400); // 400 Bad Request
//     }
//     // Sauvegarder le paiement
//     $payment->save();

//     // Retourner une réponse JSON avec le paiement créé
//     return response()->json([
//         'message' => 'Paiement ajouté avec succès.',
//         'data' => $payment
//     ], 201); // 201 Created
// }
public function createPayment(Request $request)
{
    // Validation des données reçues depuis la requête
    $validatedData = $request->validate([
        'total_amount' => 'required|numeric|min:0',
        'period' => 'required|integer|min:0|max:6',
        'month_1' => 'nullable|numeric|min:0',
        'month_2' => 'nullable|numeric|min:0',
        'month_3' => 'nullable|numeric|min:0',
        'month_4' => 'nullable|numeric|min:0',
        'month_5' => 'nullable|numeric|min:0',
        'month_6' => 'nullable|numeric|min:0',
    ]);

    // Appel de la méthode payement avec les données validées
    return $this->payement(
        $validatedData['total_amount'],
        $validatedData['period'],
        $validatedData['month_1'] ?? null,
        $validatedData['month_2'] ?? null,
        $validatedData['month_3'] ?? null,
        $validatedData['month_4'] ?? null,
        $validatedData['month_5'] ?? null,
        $validatedData['month_6'] ?? null
    );
}

public function payement($total_amount, $period, $month_1 = null, $month_2 = null, $month_3 = null, $month_4 = null, $month_5 = null, $month_6 = null)
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

    // Créer un paiement
    $payment = new payementperiodemode();
    $payment->user_id = 1; // Utilisateur connecté
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



// public function getProductsByCategory($id) {
//     try {
//         // Récupérer la catégorie par ID avec ses produits associés
//         $category = productcategories::with('products')->findOrFail($id);

//         // Vérifier si des produits existent
//         if ($category->products->isEmpty()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Aucun produit trouvé dans cette catégorie.',
//             ], 404); // 404 Not Found
//         }

//         // Retourner les produits de la catégorie
//         return response()->json([
//             'success' => true,
//             'products' => $category->products,
//         ], 200); // 200 OK

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur lors de la récupération des produits par catégorie',
//             'error' => $e->getMessage(),
//         ], 500); // 500 Internal Server Error
//     } catch (\Throwable $t) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Erreur lors de la récupération des produits par catégorie',
//             'error' => $t->getMessage(),
//         ], 500); // 500 Internal Server Error
//     }
// }

public function getProductsByCategory($id) {
    try {
        // Récupérer la catégorie par ID avec ses produits associés
        $category = productcategories::with(['products.vendor'])->find($id); // Utiliser find au lieu de findOrFail

        // Vérifier si la catégorie existe
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée.',
            ], 404); // 404 Not Found
        }

        // Vérifier si des produits existent
        if ($category->products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun produit trouvé dans cette catégorie.',
            ], 404); // 404 Not Found
        }

        // Formater les produits pour inclure les informations souhaitées
        $formattedProducts = $category->products->map(function($product) use ($category) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'stock' => $product->stock,
                'price' => $product->price,
                'vendor_name' => $product->vendor ? $product->vendor->username : '', // Nom d'utilisateur du vendeur
                'categorie_name' => $category->categories_name, // Nom de la catégorie
                'status' => $product->status,
                'product_images1' => $product->product_images1,
                'product_images2' => $product->product_images2,
                'product_images3' => $product->product_images3,
                'deleted_at' => $product->deleted_at,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        // Retourner les produits formatés
        return response()->json([
            'success' => true,
            'products' => $formattedProducts,
        ], 200); // 200 OK

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération des produits par catégorie',
            'error' => $e->getMessage(),
        ], 500); // 500 Internal Server Error
    } catch (\Throwable $t) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération des produits par catégorie',
            'error' => $t->getMessage(),
        ], 500); // 500 Internal Server Error
    }
}




}
