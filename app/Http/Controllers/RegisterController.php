<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string',
            'password' => 'required|confirmed|min:5',
        ]);

        try {

            // Obtener id del Rol User
            $userRole = Role::where('name', User::ROLE_USER)->first();

            if (!$userRole) {
                return back()->withErrors(['error' => 'El rol de usuario no está configurado.']);
            }

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id'  => $userRole->id,
                'phone'    => $validated['phone']
            ]);

            // Inicio de sesión
            Auth::login($user);

            return redirect()
                ->route('reservation')
                ->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput() // Mantiene lo que el usuario escribió
                ->withErrors(['error' => 'Ocurrió un error inesperado. Inténtalo más tarde.']);
        }
    }
}
