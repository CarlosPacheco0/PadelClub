<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Club;
use App\Models\Country;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ClubSettingsController extends Controller
{
    public function __invoke()
    {
        $club = club_info();
        $loc_club = club_location();

        $cities = City::get(['id', 'name']);
        $departments = Department::get(['id', 'name']);
        $countries = Country::get(['id', 'name']);

        return view('pages.club.club-settings', compact('club', 'loc_club', 'cities', 'departments', 'countries'));
    }

    public function update(Request $request)
    {
        $club_id = club_info()->id;

        // 1. LA VERIFICACIÓN
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:100', Rule::unique('clubs', 'name')->ignore($club_id)],
            'contact_phone'     => 'required|string|max:20',
            'city_id'           => 'required|exists:cities,id',
            'address'           => 'required|string|max:255',
            'description'       => 'nullable|string|max:255',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $club = Club::where('id', $club_id)->first();

        if ($request->hasFile('logo')) {
            // 1. Eliminar el logo anterior si existe para ahorrar espacio
            if ($club->logo_path && Storage::disk('public')->exists($club->logo_path)) {
                Storage::disk('public')->delete($club->logo_path);
            }

            // 2. Guardar el nuevo archivo en la carpeta 'logos' dentro de 'public'
            // Esto genera un nombre único automáticamente
            $path = $request->file('logo')->store('logos', 'public');
        } else {
            $path = '';
        }

        $club = club::findOrFail($club_id);

        $club->update([
            'name'          => $validated['name'],
            'contact_phone' => $validated['contact_phone'],
            'city_id'       => $validated['city_id'],
            'address'       => $validated['address'],
            'description'   => $validated['description'],
            'logo_path'     => $path
        ]);

        return back()
            ->with('success', 'Información actualizada correctamente.');
    }
}
