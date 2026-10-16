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
            'cf-turnstile-response' => 'required'
        ]);

        // Honeypot anti-bot
        if ($request->website != null) {
            return back()->withErrors(['error' => 'Bot detectado']);
        }

        // Verificación reCAPTCHA
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'form_params' => [
                'secret' => env('TURNSTILE_SECRET_KEY'),
                'response' => $request->input('cf-turnstile-response'),
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