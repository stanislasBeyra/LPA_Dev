<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationController extends Controller
{
    // public function saveToken(Request $request)
    // {

    //     auth()->user()->update(['fcm_token' => $request->token]);
    //     return response()->json(['token saved successfully.']);
    // }

    public function saveToken(Request $request)
    {
        $user = auth()->user();

        // Vérifie si le token est déjà enregistré
        // if ($user->fcm_token !== $request->token) {
        //     // Met à jour uniquement si le token est différent
        //     $user->update(['fcm_token' => $request->token]);
        //     return response()->json(['message' => 'Token enregistré avec succès.']);
        // }

        $user->update(['fcm_token' => $request->token]);
        return response()->json(['message' => 'Token enregistré avec succès.'], 200);

        // return response()->json(['message' => 'Le token existe déjà.'], 200);
    }



    public function sendNotification($id, $message)
    {
        try {
            // Initialiser les informations d'authentification Firebase
            $firebaseCredential = (new Factory)
                ->withServiceAccount(base_path('lpadev-firebase-adminsdk-begb0-e39583b9d2.json'));

            // Créer une instance de la messagerie Firebase
            $messaging = $firebaseCredential->createMessaging();

            // Récupérer l'utilisateur par ID
            $userDevice = User::find($id);

            // Vérifier si l'utilisateur existe et possède un token FCM
            if ($userDevice && !empty($userDevice->fcm_token)) {
                // Construire le message
                $notificationMessage = CloudMessage::fromArray([
                    'notification' => [
                        'title' => 'Lpa Admin',
                        'body' => $message,
                    ],
                    'token' => $userDevice->fcm_token,
                ]);

                // Envoyer le message
                $messaging->send($notificationMessage);

                // Journaliser le succès
                Log::info('Notification envoyée avec succès', [
                    'user_id' => $id,
                    'fcm_token' => $userDevice->fcm_token,
                ]);
            } else {
                // Gérer les cas où l'utilisateur ou le token est manquant
                Log::warning('Utilisateur introuvable ou token FCM manquant', [
                    'user_id' => $id,
                ]);
            }
        } catch (\Exception $e) {
            // Gérer les erreurs d'envoi de notification
            Log::error('Erreur lors de l\'envoi de la notification', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    // public function sendNotification($id, $message)
    // {

    //     $firebase_credential = (new Factory)->withServiceAccount(base_path('lpadev-firebase-adminsdk-begb0-e39583b9d2.json'));
    //     $messaging = $firebase_credential->createMessaging();
    //     $userdevice = User::find($id);
    //     if ($userdevice) {
    //         $message = CloudMessage::fromArray([

    //             'notification' => [
    //                 'title' => 'Lpa Admin',
    //                 'body' => $message,
    //             ],
    //             'token' => $userdevice->fcm_token,
    //         ]);
    //         try {
    //             $messaging->send($message);
    //         } catch (\Exception $e) {
    //             Log::error('Error sending notification', [
    //                 'error' => $e->getMessage(),
    //             ]);
    //         }
    //     }
    // }


}
