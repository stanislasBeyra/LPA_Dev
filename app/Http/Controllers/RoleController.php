<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    public function reacterole(Request $request){
        $role=new roles();
        $role->role_name='Employee';
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

public function getallrole(){
    $roles = roles::whereNull('deleted_at')->get();

    return response()->json($roles);
}

public function getvendorregisterrole(){
    $roles = roles::whereNull('deleted_at')->get();

    return response()->json($roles);
}

}
