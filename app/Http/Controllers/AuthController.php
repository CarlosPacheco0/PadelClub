<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Vista login
    public function loginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Intento de autenticación
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // Regeneración de sesión

            // Redirección según rol
            if (Auth::user()->role->name === 'admin') {
                return redirect()->route('dashboard');
            }

            return redirect()->route('home');
        }


        // Si falla el login
        return back()->withErrors([
            'email' => 'Credenciales incorrectas',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Cerrar sesión
        Auth::logout();

        $request->session()->invalidate(); // Invalidar sesión
        $request->session()->regenerateToken(); // Regenerar token CSRF

        return redirect('/');
    }
}
