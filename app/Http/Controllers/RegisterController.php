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

        // Obtener id del Rol User
        $userRole = Role::where('name', User::ROLE_USER)->first();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id'  => $userRole->id,
            'phone'    => $validated['phone']
        ]);

        // Inicio de sesión
        Auth::login($user);

        return redirect()->route('reservation');
    }
}
