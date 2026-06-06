<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    // ---------------------------------------------------------
    // 1. MOSTRAR FORMULARIO (Esta es la que te faltaba)
    // ---------------------------------------------------------
    public function loginForm() {
        return view('auth.login');
    }

    // ---------------------------------------------------------
    // 2. PROCESAR LOGIN
    // ---------------------------------------------------------
    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email|max:100',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    // ---------------------------------------------------------
    // 3. LOGOUT (Versión Limpieza Total)
    // ---------------------------------------------------------
public function logout(Request $request) {
        
        // 1. Invalidar la sesión actual (Server Side)
        // Esto "mata" el archivo en storage, haciendo inútil la cookie aunque sobreviva.
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 2. Redirigir
        return redirect('/login');
    }
}