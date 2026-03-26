<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke()
    {
        return view('auth.register');
    }

    public function player_store(Request $request)
    {
        // $validated = $request->validate([
        //     'name'     => 'required|string|max:100',
        //     'email'    => 'required|email|unique:users,email',
        //     'phone'    => 'required|string',
        //     'password' => 'required|confirmed|min:5',
        // ]);

        // try {

        //     // Obtener id del Rol User
        //     $userRole = Role::where('name', User::ROLE_USER)->first();

        //     if (!$userRole) {
        //         return back()->withErrors(['error' => 'El rol de usuario no está configurado.']);
        //     }

        //     $user = User::create([
        //         'name'     => $validated['name'],
        //         'email'    => $validated['email'],
        //         'password' => Hash::make($validated['password']),
        //         'role_id'  => $userRole->id,
        //         'phone'    => $validated['phone']
        //     ]);

        //     // Inicio de sesión
        //     Auth::login($user);

        //     return redirect()
        //         ->route('reservation')
        //         ->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');
        // } catch (\Exception $e) {
        //     return back()
        //         ->withInput() // Mantiene lo que el usuario escribió
        //         ->withErrors(['error' => 'Ocurrió un error inesperado. Inténtalo más tarde.']);
        // }
    }

    public function club_store(Request $request)
    {
        // 1. LA VERIFICACIÓN
        $validated = $request->validate([
            'club_name'     => 'required|string|max:100|unique:clubs,name',
            'address'       => 'required|string|max:255',
            'admin_name'    => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:3|max:50|confirmed',
            'terms'         => 'accepted'
        ]);

        /// 2. INICIAR LA TRANSACCIÓN (El paso clave)
        DB::beginTransaction();

        try {
            // 3. Crear primero al Usuario (El administrador del negocio)
            $user = User::create([
                'name'     => $validated['admin_name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']), // ¡Siempre encriptar!
                'role'     => 'admin_club', // Asignamos el rol por defecto
            ]);

            // 4. Crear el Club
            $club = Club::create([
                'name'     => $validated['club_name'],
                'slug'     => \Illuminate\Support\Str::slug('sport-' . $validated['club_name']), // Crea URL amigable única
                'city'     => $request->city,
                'address'  => $validated['address'],
                'is_active' => true
            ]);

            dd($club);

            // 5. Vincularlos en la tabla pivote (club_user)
            $user->clubs()->attach($club->id, ['access_level' => 'owner']);

            // 6. Confirmar que todo salió perfecto y guardar en MySQL
            DB::commit();

            // 7. Iniciar sesión automáticamente
            Auth::login($user);

            // 8. Redirigir a su nuevo panel de control
            return redirect()->route('dashboard_club')->with('success', '¡Club registrado exitosamente!');
        } catch (\Exception $e) {
            // Si CUALQUIER paso anterior falla, cancelamos todo el proceso
            DB::rollBack();

            // Lo devolvemos al formulario mostrando el error
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al registrar el club: ' . $e->getMessage()]);
        }
    }
}
