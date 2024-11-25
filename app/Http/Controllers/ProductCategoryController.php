<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\productcategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProductCategoryController extends Controller
{
    //

    public function viecatgeroie()
    {
        return  view('adminComponent.manage-categorie');
    }

    //     public function addCategories(Request $request)
    // {
    //     try {
    //         // Validation des données de la requête
    //         $validatedData = $request->validate([
    //             'name' => 'required|string|max:255', // Le nom de la catégorie est requis
    //             'description' => 'nullable|string|max:500', // La description est optionnelle
    //         ]);


    //         // Vérification de l'existence de la catégorie en ignorant la casse
    //         $categorys = ProductCategories::whereRaw('LOWER(categories_name) = ?', [strtolower($validatedData['name'])])
    //             ->first();

    //         if ($categorys) {
    //             // Si la catégorie existe déjà, rediriger avec un message d'erreur
    //             return redirect()->route('content.page')->with('error', 'The category already exists.');
    //         }


    //         // Création de la catégorie avec les données validées
    //         $category = new ProductCategories();
    //         $category->categories_name = $validatedData['name'];
    //         $category->categories_description = $validatedData['description'] ?? null; // Ajouter la description si fournie
    //         $category->save();
    //        // dd($category);

    //         // Redirection vers la page de gestion des catégories avec un message de succès
    //         return redirect()->route('content.page', ['page' => 'manage-categories'])->with('success', 'Category added successfully.');

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Gestion des erreurs de validation, rediriger avec un message d'erreur
    //         return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'Validation error : ' . $e->getMessage());
    //     } catch (\Exception $e) {
    //         // Gestion des autres exceptions, rediriger avec un message d'erreur générique
    //         return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'An error occurred. Please try again: ' . $e->getMessage());
    //     }
    // }

    public function addCategories(Request $request)
    {
        try {
            // Validation des données de la requête
            $validatedData = $request->validate([
                'name' => 'required|string|max:255', // Le nom de la catégorie est requis
                'description' => 'nullable|string|max:500', // La description est optionnelle
            ]);

            // Vérification de l'existence de la catégorie en ignorant la casse
            $categorys = ProductCategories::whereRaw('LOWER(categories_name) = ?', [strtolower($validatedData['name'])])
                ->first();

            if ($categorys) {
                // Si la catégorie existe déjà, rediriger avec un message d'erreur
                return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'The category already exists.');
            }

            // Création de la catégorie avec les données validées
            $category = new ProductCategories();
            $category->categories_name = $validatedData['name'];
            $category->categories_description = $validatedData['description'] ?? null; // Ajouter la description si fournie
            $category->save();

            // Redirection vers la page de gestion des catégories avec un message de succès
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('success', 'Category added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Gestion des erreurs de validation, rediriger avec un message d'erreur
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'Validation error : ' . $e->getMessage());
        } catch (\Exception $e) {
            // Gestion des autres exceptions, rediriger avec un message d'erreur générique
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'An error occurred. Please try again: ' . $e->getMessage());
        }
    }

    public function updateCategories(Request $request)
    {
        try {
            // Vérification de l'authentification de l'utilisateur
            $UserVendor = Auth::user();
            if (!$UserVendor) {
                return back()->with('error', 'You are not authenticated.');
            }

            // Validation des données de la requête
            $validatedData = $request->validate([
                'categories_name' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:255' // Correction de "decription" en "description"
            ]);

            // Récupérer la catégorie par ID
            $category = ProductCategories::find($request->categoryid); // Assurez-vous que la classe est en PascalCase

            // Vérifier si la catégorie existe
            if (!$category) {
                return back()->with('error', 'Category not found.');
            }

            // Vérifier si une catégorie avec le même nom existe déjà (insensible à la casse)
            if (isset($validatedData['categories_name'])) {
                $existingCategory = ProductCategories::where('categories_name', 'LIKE', $validatedData['categories_name'])
                    ->where('id', '!=', $category->id)
                    ->first();
                if ($existingCategory) {
                    return back()->with('error', 'A category with this name already exists.');
                }
            }

            // Mise à jour des informations de la catégorie
            if (isset($validatedData['categories_name'])) {
                $category->categories_name = $validatedData['categories_name'];
            }
            if (isset($validatedData['description'])) { // Utilisation de 'description' au lieu de 'decription'
                $category->categories_description = $validatedData['description'];
            }
            $category->save();

            // Retourner une réponse JSON après la mise à jour réussie
            return back()->with('success', 'Category updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Validation failed.');
            // Gestion des erreurs de validation

        } catch (\Exception $e) {
            // Gestion des autres exceptions
            return back()->with('error', 'An unexpected error occurred.');
        }
    }

    public function deleteCategories(Request $request)
    {
        try {
            $UserVendor = Auth::user();
            if (!$UserVendor) {
                return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'You are not authenticated.');
            }
            $category = productcategories::find($request->categoryId);
            if (!$category) {
                return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'Category not found.');
            }
            $productset=Product::where('categorie_id',$category->id)->first();
            if($productset) {
                return back()->with('error', 'This category cannot be deleted because it is already linked to an existing product.');
            }
            
            $category->delete();
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            // Gestion des erreurs générales
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'An unexpected error occurred.' . $e->getMessage());
        }
    }
}
