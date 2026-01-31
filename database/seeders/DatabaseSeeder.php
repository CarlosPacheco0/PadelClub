<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Field;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Llamar solo un Seeder
        // $this->call( UserSeeder::class );

        // Llamar más de un Seeder
        $this->call([
            RoleSeeder::class,
            UserSeeder::class
        ]);


        // Llamar el Factory
        // Field::factory(5)->create(); // Canchas
        Schedule::factory(6)->create(); // Horarios


    }
}
