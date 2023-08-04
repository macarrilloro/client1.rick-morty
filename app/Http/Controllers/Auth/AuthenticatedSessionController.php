<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Traits\Token;

class AuthenticatedSessionController extends Controller
{
    use Token;
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)//: RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json'
        ])->post('http://api.rick-morty.test/v1/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);
        if ($response->status() == 404) {
            return back()->withErrors(['message'=>'Estas credenciales no coinciden con nuestros registros.']);
        }

        $service = $response->json();
        $user = User::updateOrCreate([
            'email' => $request->email
        ],$service['data']);
        if (!$user->accessToken) {
            $this->setAccessToken($user, $service);
        }

        Auth::login($user, $request->remember);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
