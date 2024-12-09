<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    //

    public function reacterole(Request $request)
    {
        $role = new roles();
        $role->role_name = 'Employee';
        $role->save();
    }

    public function deleterole($id)
    {
        // Trouver le rôle par son ID
        $role = roles::find($id);

        // Vérifiez si le rôle existe avant d'essayer de le supprimer
        if ($role) {
            $role->delete(); // Cela marque le rôle comme supprimé sans le retirer de la base de données
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    public function ForcedeleteRole($id)
    {
        // Trouver le rôle par son ID
        $role = roles::find($id);

        // Vérifiez si le rôle existe avant de tenter de le supprimer
        if ($role) {
            $role->forceDelete(); // Supprimer définitivement le rôle
            return response()->json(['message' => 'Role deleted permanently'], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    public function rolfoceRole($id)
    {
        // Trouver le rôle par son ID, même s'il est soft deleted
        $role = roles::withTrashed()->find($id);

        // Vérifiez si le rôle existe avant de tenter de le supprimer
        if ($role) {
            $role->forceDelete(); // Supprimer définitivement le rôle
            return response()->json(['message' => 'Role deleted permanently'], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }


    public function showDeletedRoles()
    {
        // Récupérer les rôles où deleted_at n'est pas NULL
        $deletedRoles = roles::onlyTrashed()->get();

        return response()->json($deletedRoles, 200);
    }



    public function restoreRole($id)
    {
        // Trouver le rôle supprimé par son ID
        $role = roles::withTrashed()->find($id);

        // Vérifiez si le rôle existe
        if ($role) {
            $role->restore(); // Cela restaure le rôle supprimé
            return response()->json(['message' => 'Role restored successfully'], 200);
        } else {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    public function getallrole()
    {
        $roles = roles::whereNull('deleted_at')->get();

        return response()->json($roles);
    }

    public function getvendorregisterrole()
    {
        $roles = roles::whereNull('deleted_at')->get();

        return response()->json($roles);
    }

    public function addroles(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'role_name' => 'required|string|max:255|unique:roles,role_name',
                'description' => 'nullable|string|max:500',
            ]);
            roles::create($validatedData);
            return back()->with('success', 'Role created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error creating role: " . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again later.');
        }
    }

    public function updateRole(Request $request)
{
    try {
        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $request->roleId,
            'description' => 'nullable|string|max:500',
        ]);

        $role = roles::findOrFail($request->roleId);
        if(!$role){
            return back()->with('success', 'Role Not found.');
        }

        $role->update($validatedData);

        return back()->with('success', 'Role updated successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error("Error updating role: " . $e->getMessage());
        return back()->with('error', 'An error occurred. Please try again later.');
    }
}

public function deleteRoles(Request $request)
{
    try {
        $role = roles::findOrFail($request->roleId);

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    } catch (\Exception $e) {
        Log::error("Error deleting role: " . $e->getMessage());
        return back()->with('error', 'An error occurred. Please try again later.');
    }
}

public function searchRole(Request $request)
    {
       try{
        $searchTerm = $request->input('search');

        // Construire la requête initiale
        $query = roles::where('id', '!=', 2)
            ->orderBy('id', 'desc');
 
        // Appliquer le filtre de recherche si un terme est fourni
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('role_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Exécuter la requête et récupérer les résultats
        $roles = $query->get();

        return response()->json(['roles'=>$roles],200);
       }catch(\Throwable $t){
        return response()->json([
            'success'=>false,
            'message' => 'An error occurred: ' . $t->getMessage(),
        ],500);
       }
    }


}
