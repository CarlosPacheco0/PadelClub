<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $country = Country::create(['name' => 'Colombia', 'code' => 'COL']);

        $dept = Department::create([
            'name' => 'Norte de Santander',
            'country_id' => $country->id
        ]);

        City::create([
            'name' => 'Ocaña',
            'department_id' => $dept->id
        ]);
    }
}
