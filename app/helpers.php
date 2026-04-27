<?php // <--- ¡No olvides esta etiqueta!

use App\Models\Club;
use Illuminate\Support\Facades\Auth;

if (!function_exists('club_info')) {
    function club_info()
    {
        return Auth::user()->clubs->first();
    }

    function club_location()
    {
        $club =  club_info();
        return [
            'city_id'    => $club->city_id,
            'dep_id'     => $club->city->department_id,
            'country_id' => $club->city?->department->country_id
        ];
    }
}
