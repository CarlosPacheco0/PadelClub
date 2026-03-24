<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Perfil Superadministrador (Tú)
        User::create([
            'name' => 'Super Admin Maestro',
            'email' => 'admin@sportbook.com',
            'password' => Hash::make('password123'), // Contraseña estándar para pruebas
            'phone' => '3001234567',
            'role' => 'superadmin',
        ]);

        // 2. Perfil Administrador de Club (Dueño de escenario)
        User::create([
            'name' => 'Carlos Dueño',
            'email' => 'club@sportbook.com',
            'password' => Hash::make('password123'),
            'phone' => '3120000000',
            'role' => 'admin_club',
        ]);

        // 3. Perfil Usuario Final (Deportista que va a reservar)
        User::create([
            'name' => 'Andrés Deportista',
            'email' => 'andres@correo.com',
            'password' => Hash::make('password123'),
            'phone' => '3209876543',
            'role' => 'usuario',
        ]);
    }
}
