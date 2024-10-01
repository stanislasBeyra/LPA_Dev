<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\order_items;
use App\Models\Product;
use App\Models\ProductCategory;
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

    public function getVendorProducts()
    {
        try {
            // Retrieve the currently authenticated user
            $uservendor = Auth::user();

            if (!$uservendor) {
                // Return a JSON response if the vendor is not found
                return response()->json([
                    'success' => false,
                    'message' => 'Your are not authenticated.'
                ], 404);
            }
            // Retrieve products associated with the vendor
            $products = Product::where('vendor_id', $uservendor->id)
                ->latest()
                ->take(10)
                ->get();
            // Return a JSON response with the products
            return response()->json([
                'success' => true,
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return a JSON error response
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
                "delete"=>$product

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

    public function addcategory(Request $request)
    {
        try {
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

            // Vérification de l'existence de la catégorie en ignorant la casse
            $category = ProductCategory::whereRaw('LOWER(categories_name) = ?', [strtolower($request->categories_name)])
                ->first();

            if ($category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category already exists.'
                ], 409);  // Conflit HTTP
            }

            // Création de la catégorie avec les données validées
            $category = new ProductCategory();
            $category->categories_name = $validatedData['categories_name'];
            $category->save();

            // Retourner une réponse JSON après la création réussie
            return response()->json([
                'success' => true,
                'message' => 'Category added successfully.',
                'category' => $category
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

    public function getCategory()
    {
        try {

            $UserVendor = Auth::user();
            if (!$UserVendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authenticated.'
                ], 404);
            }
            // Récupération de la catégorie en fonction de l'ID
            $category = ProductCategory::all();
            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully.',
                'category' => $category
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
            $category = ProductCategory::find($id);
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
            $category = ProductCategory::find($id);

            // Vérifier si la catégorie existe
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found.'
                ], 404);
            }

            // Vérifier si une catégorie avec le même nom existe déjà (insensible à la casse)
            $existingCategory = ProductCategory::where('categories_name', 'LIKE', $validatedData['categories_name'])
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
        $orders = Order::whereHas('items', function ($query) use ($products) {
            $query->whereIn('product_id', $products);
        })->with(['items.product', 'user'])->get();

        // Parcourir chaque commande pour ajouter les produits associés
        $orderDetails = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'total_price' => $order->total_price,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'products' => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product->id,
                        'product_name' => $item->product->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                })
            ];
        });

        // Retourner les détails des commandes avec les produits
        return response()->json(['success' => true, 'orders' => $orderDetails], 200);
    } catch (\Exception $e) {
        Log::error('Error fetching vendor orders', ['exception' => $e]);
        return response()->json(['success' => false, 'message' => 'An error occurred while fetching orders'], 500);
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


}
