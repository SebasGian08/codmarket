<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscriptions,email',
            'g-recaptcha-response' => 'required'
        ]);

        // Honeypot anti-bot
        if ($request->website != null) {
            return back()->withErrors(['error' => 'Bot detectado']);
        }

        // Verificación reCAPTCHA
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip()
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (!isset($responseData['success']) || !$responseData['success']) {
            return back()->withErrors(['error' => 'Verificación anti-robot fallida']);
        }
        
        Subscription::create([
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        return back()->with('success', 'Te has suscrito correctamente');
    }
}