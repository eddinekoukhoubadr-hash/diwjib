<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion avec vérification reCAPTCHA
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validation du formulaire y compris le captcha
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required'],
        ]);

        // Vérification reCAPTCHA côté serveur
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (! $response->json('success')) {
            return back()->withErrors([
                'captcha' => 'Échec de la vérification reCAPTCHA. Veuillez réessayer.',
            ]);
        }

        // Authentification
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Déconnexion
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
