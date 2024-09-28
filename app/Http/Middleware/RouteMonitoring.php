<?php

namespace App\Http\Middleware;

use App\Models\loginhistory;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Jenssegers\Agent\Agent;

class RouteMonitoring
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Récupérer l'adresse IP de l'utilisateur
        $ip = $request->ip();

        // Récupérer la route actuelle
       // $route = $request->path();
        $route = stripslashes($request->path());

        // Récupérer la date et l'heure
        $dateTime = now()->toDateTimeString();

        // Récupérer la localisation via une API (par exemple ipinfo.io)
        $location = $this->getIpLocation($ip);
       /// dd($location);

       $device = $this->getDeviceDetails($request);
        // Construire les données à stocker
        $data = [
            'ip' => $ip,
            'user_id' => $user ? $user->id : null, // Vérification ajoutée
            'route' => $route,
            'date' => $dateTime,
            'location' => $location,
            'device' => $device,
        ];

// Enregistrer les données dans la base de données

LoginHistory::create([
    'user_id' => 22,
    'login_time' => $dateTime,
    'ip_address' => $ip,
    'device' =>$device['browser'].' '.$device['platform'], // Encode device details as JSON
]);

// if ($user) {
//     LoginHistory::create([
//         'user_id' => $user->id,
//         'login_time' => $dateTime,
//         'ip_address' => $ip,
//         'device' => json_encode($device), // Encode device details as JSON
//     ]);
// }


        // Appeler la méthode pour stocker les données dans un fichier JSON
        $this->storeRouteData($data);

        return $next($request);
    }

    private function getIpLocation($ip)
    {
        try {
            $client = new Client();
            // Remplacez YOUR_TOKEN par votre token d'authentification
            $response = $client->get("https://ipinfo.io/{$ip}?token=696bb438defa1d");
            $locationData = json_decode($response->getBody(), true);

            // Vérifier si la requête a réussi
            if (isset($locationData['ip'])) {

                return [
                    'country' => $locationData['country'] ?? null,
                    'region' => $locationData['region'] ?? null,
                    'city' => $locationData['city'] ?? null,
                    'latitude' => $locationData['loc'] ? explode(',', $locationData['loc'])[0] : null,
                    'longitude' => $locationData['loc'] ? explode(',', $locationData['loc'])[1] : null,
                ];

            } else {
                Log::error('API Error', [
                    'status' => 'fail',
                    'message' => $locationData['message'] ?? 'No message provided',
                ]);
                return ['error' => 'Unable to retrieve location'];
            }
        } catch (\Exception $e) {
            Log::error('Exception', [
                'message' => $e->getMessage(),
                'ip' => $ip,
            ]);
            return ['error' => 'Unable to retrieve location'];
        }
    }




    private function getDeviceDetails(Request $request)
    {
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));

        return [
            'browser' => $agent->browser(), // Nom du navigateur
            'browser_version' => $agent->version($agent->browser()), // Version du navigateur
            'platform' => $agent->platform(), // Système d'exploitation
            'platform_version' => $agent->version($agent->platform()), // Version du système d'exploitation
            'is_mobile' => $agent->isMobile() ? 'Yes' : 'No', // Si c'est un mobile
            'is_tablet' => $agent->isTablet() ? 'Yes' : 'No', // Si c'est une tablette
        ];
    }

    // Stocker les données dans un fichier JSON
    private function storeRouteData($data)
{
    $file = storage_path('logs/route_monitoring.json');

    // Vérifier si le fichier existe, sinon le créer
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
    }

    // Charger le contenu actuel du fichier
    $currentData = json_decode(file_get_contents($file), true);

    // Ajouter les nouvelles données
    $currentData[] = $data;

    // Enregistrer les données mises à jour dans le fichier sans échapper les slashes
    file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}


}


