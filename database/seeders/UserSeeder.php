<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $roleAdmin = Role::where('name', 'admin')->firstOrFail();
        $roleUser = Role::where('name', 'user')->firstOrFail();

         $user = User::create([
            'name'     => 'Carlos',
            'email'    => 'carlangas@gmail.com',
            'password' => Hash::make('12345'),
            'role_id'  => $roleAdmin->id,
            'phone'    => ''
        ]);

        $user = User::create([
            'name'     => 'Pepito',
            'email'    => 'pepito@gmail.com',
            'password' => Hash::make('12345'),
            'role_id'  => $roleUser->id,
            'phone'    => ''
        ]);
    }
}
