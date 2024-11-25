<?php

namespace App\Http\Controllers\webLaravel;

use App\Http\Controllers\Controller;
use App\Models\agence;
use App\Models\productcategories;
use App\Models\roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;


class HomeController extends Controller
{
    protected $productController;
    protected $orderController;

    public function __construct(ProductController $productController,OrderController $orderController)
    {
        $this->productController = $productController;
        $this->orderController = $orderController;
    }

    

    public function loginform()
    {
        return view('auths.login');
    }
    public function getUsers()
    {
        // Exemple de données d'utilisateurs avec solde
        $users = [
            ['id' => 1, 'username' => 'Alice', 'mobile' => '225012345678', 'status' => 'Active', 'balance' => 10500],
            ['id' => 2, 'username' => 'John', 'mobile' => '225077654321', 'status' => 'Inactive', 'balance' => 1200],
            ['id' => 3, 'username' => 'Sarah', 'mobile' => '225075612345', 'status' => 'Active', 'balance' => 300],
            ['id' => 4, 'username' => 'James', 'mobile' => '225057893456', 'status' => 'Banned', 'balance' => 0],
            ['id' => 5, 'username' => 'Michael', 'mobile' => '225056543210', 'status' => 'Active', 'balance' => 500],
            ['id' => 6, 'username' => 'Alice', 'mobile' => '225012345678', 'status' => 'Active', 'balance' => 1500],
            ['id' => 7, 'username' => 'John', 'mobile' => '225057654321', 'status' => 'Inactive', 'balance' => 1200],
            ['id' => 8, 'username' => 'Sarah', 'mobile' => '225075612345', 'status' => 'Active', 'balance' => 300],
            ['id' => 9, 'username' => 'James', 'mobile' => '225017893456', 'status' => 'Banned', 'balance' => 0],
            ['id' => 10, 'username' => 'Michael', 'mobile' => '225016543210', 'status' => 'Active', 'balance' => 500],
        ];

        // Nombre total d'utilisateurs
        $totalUsers = count($users);

        // Utilisateurs avec un numéro de téléphone
        $usersWithPhone = array_filter($users, function ($user) {
            return !empty($user['mobile']);
        });

        // Nombre d'utilisateurs actifs, inactifs et bannis
        $activeUsers = array_filter($users, function ($user) {
            return $user['status'] === 'Active';
        });
        $inactiveUsers = array_filter($users, function ($user) {
            return $user['status'] === 'Inactive';
        });
        $bannedUsers = array_filter($users, function ($user) {
            return $user['status'] === 'Banned';
        });

        // Utilisateurs avec un solde supérieur à 10 000
        $usersWithBalanceAbove10000 = array_filter($users, function ($user) {
            return $user['balance'] > 10000;
        });

        $countOrange = count(array_filter($users, function ($user) {
            return strpos($user['mobile'], '22507') === 0; // Vérifie si le numéro commence par 22507
        }));
        $countmtn = count(array_filter($users, function ($user) {
            return strpos($user['mobile'], '22501') === 0; // Vérifie si le numéro commence par 22507
        }));
        $countmoov = count(array_filter($users, function ($user) {
            return strpos($user['mobile'], '22505') === 0; // Vérifie si le numéro commence par 22507
        }));

        // Retourner toutes les données à la vue
        return [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'usersWithPhone' => $usersWithPhone,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'bannedUsers' => $bannedUsers,
            'usersWithBalanceAbove10000' => $usersWithBalanceAbove10000,
            'countOrange' => $countOrange,
            'countmtn' => $countmtn,
            'countmoov' => $countmoov,
        ];
    }

    public function getTransactions()
    {
        $transactions = [
            ['id' => 1, 'sender' => 'Alice', 'receiver' => 'Bob', 'amount' => 100, 'date' => '2024-11-01', 'status' => 'Completed'],
            ['id' => 2, 'sender' => 'John', 'receiver' => 'Mary', 'amount' => 250, 'date' => '2024-11-02', 'status' => 'Pending'],
            ['id' => 3, 'sender' => 'Sarah', 'receiver' => 'Tom', 'amount' => 150, 'date' => '2024-11-03', 'status' => 'Completed'],
            ['id' => 4, 'sender' => 'James', 'receiver' => 'Emily', 'amount' => 200, 'date' => '2024-11-04', 'status' => 'Failed'],
            ['id' => 5, 'sender' => 'Michael', 'receiver' => 'Sophia', 'amount' => 300, 'date' => '2024-11-05', 'status' => 'Completed'],
        ];

        return $transactions;
    }

    public function getNewCategories()
    {
        try {
            // Récupérer toutes les catégories de produits
            $categories = productcategories::orderBY('id', 'desc')->get(); // Utilisation de 'all()' pour récupérer toutes les catégories

            // Retourner la vue avec les catégories
            return $categories;
        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs pour le débogage
            Log::error('Error fetching categories: ' . $e->getMessage());

            // Rediriger avec un message d'erreur générique
            return redirect()->route('view.categories')->with('error', 'An error occurred while fetching categories. Please try again later.');
        }
    }

    public function getvendorregisterrole()
    {
        $roles = roles::whereNull('deleted_at')->get();  // Corrigez si nécessaire

        return $roles;
    }


    public function getUsersWithVendors()
{
    // Récupérer tous les utilisateurs avec leurs fournisseurs associés et leurs rôles
    $users = User::with(['vendor', 'role'])
        ->where('role',3)
        ->orderBy('id', 'desc')
        ->get();

        // dd($users->map(function ($user) {
        //     return $user->role;  // Accède à la relation 'role' de chaque utilisateur
        // }));
    // Retourner les utilisateurs avec les relations chargées
    return $users;
}


    public function getagences()
    {
        $agences = agence::orderBy('id', 'desc')->get();
        return $agences;
    }

    public function getproductCategorie(){
        $categories = productcategories::where('status', 1)
        ->orderBy('categories_name', 'asc')
        ->get();

        return $categories;
    }


    public function getContent($page)
    {
        // Récupérer les utilisateurs et les transactions
        $userData = $this->getUsers();
        $transactions = $this->getTransactions();
        $categories = $this->getNewCategories();
        $roles = $this->getvendorregisterrole();
        $vendors = $this->getUsersWithVendors();
        $agences=$this->getagences();
        $categories=$this->getproductCategorie();

        $products=$this->productController->getallvendorcoonectproduct();
        $orders=$this->orderController->getOrders();

        // get vendor product for admin
        $vendorproducts=$this->productController->getNewallvendorProducts();

        //  dd($roles);
        // Sélectionner la vue en fonction de la page
        switch ($page) {
            case 'index':
                return view('index');
            case 'home':
                return view('homecontent', [
                    'users' => $userData['users'],
                    'totalUsers' => $userData['totalUsers'],
                    'usersWithPhone' => $userData['usersWithPhone'],
                    'activeUsers' => $userData['activeUsers'],
                    'inactiveUsers' => $userData['inactiveUsers'],
                    'bannedUsers' => $userData['bannedUsers'],
                    'countOrange' => $userData['countOrange'],
                    'countmtn' => $userData['countmtn'],
                    'countmoov' => $userData['countmoov'],
                    'usersWithBalanceAbove10000' => $userData['usersWithBalanceAbove10000'],
                ]);
            case 'historique':
                return view('transfert.historique', ['transactions' => $transactions]);
            case 'manage-vendors':
                return view('adminComponent.manage-vendor', compact('roles', 'vendors'));
                
            case 'manage-employees':
                return view('adminComponent.manage-employee');
            case 'manage-agencies':
                return view('adminComponent.manage-agencies',compact('agences'));
            case 'manage-categories':
                return view('adminComponent.manage-categorie', ['categories' => $categories]);
            case 'vendor-product':
                return view('adminComponent.vendor-product',compact('vendorproducts'));
            case 'vendor-order':
                return view('adminComponent.vendor-order');

            case 'employee-paiement':
                return view('adminComponent.employee-paiement');
            case 'user-profile':
                return view('profil');

            case'manage-vendor-product':
                return view('vendorComponent.manage-vendor-product',compact('categories','products'));
            case 'manage-vendor-orders':
                return view('vendorComponent.manage-vendor-order',compact('orders')); 

            case 'historiquemobile':
                return view('transfert.historiquemobilemonney', ['transactions' => $transactions]);
            default:
                return abort(404);
        }
    }
}
