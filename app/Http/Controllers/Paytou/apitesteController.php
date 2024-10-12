<?php

namespace App\Http\Controllers\Paytou;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class apitesteController extends Controller
{
    public function testeapi()
    {
        try {
            $url = 'https://api.officepartnerspaytou.com/api/teste';
            $username = 'ITQVFAZONG';
            $password = 'GhEf8Iunl1';

            // Envoi de la requête avec authentification de base
            $response = Http::withBasicAuth($username, $password)
                ->withOptions(['verify' => false]) // À utiliser avec précaution en production
                ->get($url);

            // Vérification du statut de la réponse
            if ($response->successful()) {
                return response()->json([
                    'response' => $response->json(),
                ]);
            } else {
                // Gérer les réponses non réussies
                return response()->json([
                    'error' => 'Request failed',
                    'status' => $response->status(),
                    'message' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Gestion des exceptions
            return response()->json(
                [
                    'error' => 'Internal Server Error',
                    'message' => $e->getMessage() // Optionnel pour le débogage
                ],
                500 // Code d'erreur pour une erreur interne du serveur
            );
        }
    }

    public function getUser(Request $request){
        try{
            $url = 'https://api.officepartnerspaytou.com/api/get/Client/Racine/Byphone';
            $username = 'ITQVFAZONG';
            $password = 'GhEf8Iunl1';
            $data=[
                'mobile'=>$request->mobile,
            ];
            // Envoi de la requête avec authentification de base
            $response = Http::withBasicAuth($username, $password)
                ->withOptions(['verify' => false]) // À utiliser avec précaution en production
                ->post($url,$data);
                if ($response->successful()) {
                    return response()->json([
                        'response' => $response->json(),
                    ]);
                } else {
                    // Gérer les réponses non réussies
                    return response()->json([
                        'error' => 'Request failed',
                        'status' => $response->status(),
                        'message' => $response->body()
                    ], $response->status());
                }

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
                ], 500);
        }
    }

    public function fraisdossier(Request $request){
        try{
            $url = 'https://api.officepartnerspaytou.com/api/send/Code/FraisDossier';
            $username = 'ITQVFAZONG';
            $password = 'GhEf8Iunl1';
            $data=[
                'mobile'=>$request->mobile,
                'amount'=>$request->montant
            ];
            $response = Http::withBasicAuth($username, $password)
                ->withOptions(['verify' => false]) // À utiliser avec précaution en production
                ->post($url,$data);
                if ($response->successful()) {
                    return response()->json([
                        'response' => $response->json(),
                    ]);
                } else {
                    // Gérer les réponses non réussies
                    return response()->json([
                        'error' => 'Request failed',
                        'status' => $response->status(),
                        'message' => $response->body()
                    ], $response->status());
                }
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
                ], 500);
        }catch(\Throwable $t){
            return response()->json([
                'error' => $t->getMessage()
                ], 500);
        }
    }
    //get/Historique/Commission

    public function getHistoriqueCommission(){
        try{
            $url = 'https://api.officepartnerspaytou.com/api/get/Historique/Commission';
            $username = 'ITQVFAZONG';
            $password = 'GhEf8Iunl1';

            // Envoi de la requête avec authentification de base
            $response = Http::withBasicAuth($username, $password)
                ->withOptions(['verify' => false]) // À utiliser avec précaution en production
                ->get($url);

            // Vérification du statut de la réponse
            if ($response->successful()) {
                return response()->json([
                    'response' => $response->json(),
                ]);
            } else {
                // Gérer les réponses non réussies
                return response()->json([
                    'error' => 'Request failed',
                    'status' => $response->status(),
                    'message' => $response->body()
                ], $response->status());
            }
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
                ], 500);
        }catch(\Throwable $t){
            return response()->json([
                'error' => $t->getMessage()
                ], 500);
        }
    }

}
