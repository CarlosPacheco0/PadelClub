<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Str;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el Usuario Administrador del Club
        $admin = User::create([
            'name'     => 'Admin de Prueba',
            'email'    => 'admin@padelclub.com',
            'phone'    => '3201234567',
            'password' => Hash::make('password123'),
            'role'     => 'admin_club',
        ]);

        // 2. Crear el Comercio (Club)
        $clubName = 'Padel Center Norte';

        $club = Club::create([
            'name'          => $clubName,
            'slug'          => \Illuminate\Support\Str::slug($clubName . '-' . rand(100, 999)),
            'city'          => 'Ocaña',
            'address'       => 'Sector El Bosque, Vía Principal',
            'contact_phone' => '3201234567',
            'is_active'     => true,
            // Si tienes la columna settings como JSON, puedes inicializarla vacía
            // 'settings'      => json_encode([]), 
        ]);

        // 3. Vincular el administrador con el club
        $admin->clubs()->attach($club->id, ['access_level' => 'owner']);
    }
}
