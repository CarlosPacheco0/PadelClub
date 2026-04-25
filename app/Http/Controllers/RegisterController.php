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
        // 1. Validación estricta
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:3|max:255|confirmed',
        ]);

        try {
            // 2. Crear al usuario
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role'     => 'usuario',
            ]);

            // 3. Iniciar sesión automáticamente
            Auth::login($user);

            // 4. Redirigir a la vista principal de reservas (Corregido el error de tipeo)
            return redirect()
                ->route('martketplace')
                ->with('login', '¡Tu cuenta ha sido creada exitosamente!');
                
        } catch (\Exception $e) {

            // Si algo falla, lo devolvemos con el error visible
            return back()
                ->withInput()
                ->withErrors(['error' => 'Ocurrió un error al registrar la cuenta: ' . $e->getMessage()]);

        }
    }

    public function club_store(Request $request)
    {
        // 1. LA VERIFICACIÓN
        $validated = $request->validate([
            'club_name'     => 'required|string|max:100|unique:clubs,name',
            'city'          => 'required|string',
            'address'       => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
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
                'name'          => $validated['club_name'],
                'slug'          => \Illuminate\Support\Str::slug($validated['club_name'] . '-' . rand(100, 999)),
                'city'          => $validated['city'],
                'address'       => $validated['address'],
                'contact_phone' => $validated['contact_phone'],
                'is_active'     => true
            ]);

            // 5. Vincularlos en la tabla pivote (club_user)
            $user->clubs()->attach($club->id, ['access_level' => 'owner']);

            // 6. Confirmar que todo salió perfecto y guardar en MySQL
            DB::commit();

            // 7. Iniciar sesión automáticamente
            Auth::login($user);

            // 8. Redirigir a su nuevo panel de control
            return redirect()
                ->route('club_settings')
                > with('login', '¡Club registrado exitosamente!');
        } catch (\Exception $e) {
            // Si CUALQUIER paso anterior falla, cancelamos todo el proceso
            DB::rollBack();

            // Lo devolvemos al formulario mostrando el error
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al registrar el club: ' . $e->getMessage()]);
        }
    }
}
