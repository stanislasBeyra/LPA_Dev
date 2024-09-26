<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Log;

class EmailController extends Controller
{
    /**
     * Envoie un e-mail en utilisant l'API Mailtrap.
     *
     * @param array $data
     * @return bool
     */
    protected function sendEmail(array $data): bool
    {
        $client = new Client();

        try {
            $response = $client->post('https://send.api.mailtrap.io/api/send', [
                'headers' => [
                    'Authorization' => 'Bearer a94d295526aaa76d5afad11dc7d6c307',
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            // Vérifie le statut de la réponse et retourne true si la réponse est 200
            if ($response->getStatusCode() == 200) {
                return true;
            } else {
                \Log::error('Failed to send email. Status Code: ' . $response->getStatusCode());
                return false;
            }
        } catch (RequestException $e) {
            // Gère les exceptions et enregistre les erreurs
            \Log::error('RequestException: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            // Gère les autres exceptions
            \Log::error('Exception: ' . $e->getMessage());
            return false;
        }
    }

    public function sendTestEmail($user,$password)
    {
        $data = [
            'from' => [
                'email' => 'mailtrap@demomailtrap.com',
                'name' => 'LPA Center',
            ],
            'to' => [
                ['email' => $user->email],
            ],
            'subject' => 'Your Login Information',
            'html' => <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Login Information</title>
    <style>
        /* Inline styles for email compatibility */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .container {
            width: 100%;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #008080;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .card-header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .card-header h3 {
            margin: 0;
        }
        .card-body {
            padding: 20px;
        }
        .alert-info {
            padding: 15px;
            background-color: #e7f0ff;
            border: 1px solid #b8daff;
            border-radius: 4px;
            color: #31708f;
        }
        .card-footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Your Login Information</h3>
            </div>
            <div class="card-body">
                <p>Hi {$user->username},</p>
                <p>We are pleased to provide you with your login details for the LPA app. Please find your credentials below:</p>
                <div class="alert-info">
                    <p><strong>Username:</strong> {$user->username}</p>
                    <p><strong>Password:</strong> {$password}</p>
                </div>
                <p>If you have any questions or need further assistance, feel free to reach out to our support team.</p>
                <p>You can visit our support page for more information: <a href="http://192.168.1.4:1206">Support Page</a></p>
            </div>
            <div class="card-footer">
                &copy; 2024 LPA Center. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
HTML,
        ];

        $result = $this->sendEmail($data);

        return $result ? 'Email sent successfully!' : 'Failed to send email. Please check the logs for details.';
    }
}
