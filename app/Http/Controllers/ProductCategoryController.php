<?php

namespace App\Http\Controllers;

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
            $category->delete();
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('success', 'Category deleted successfully.');
            
        } catch (\Exception $e) {
            // Gestion des erreurs générales
            return redirect()->route('content.page', ['page' => 'manage-categories'])->with('error', 'An unexpected error occurred.'.$e->getMessage());
        }
    }
}
