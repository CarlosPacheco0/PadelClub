<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RateManagementController extends Controller
{
    public function __invoke()
    {

        $rates = Rate::orderBy('id', 'asc')
            ->get();

        return view('pages.admin.rateManagement', compact('rates'));
    }

    public function store(Request $request)
    {
        // 1. Convertir la selección del usuario en un array de números de días
        // Ejemplo: Si selecciona "Lunes a Viernes", obtenemos [1, 2, 3, 4, 5]
        $daysSelected = $this->getNumDays($request->days);

        // 2. Definir el intervalo de tiempo (ej: cada 1 hora)
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $price = $request->price;

        // 3. Recorrer los días seleccionados (Lunes, Martes...)
        foreach ($daysSelected as $dayNum) {
            // Usamos updateOrCreate para no duplicar si ya existe
            Rate::create([
                'day_week'     => $dayNum,
                'start_time'   => $start_time,
                'end_time'     => $end_time,
                'price'        => $price
            ]);
        }

        return redirect()
            ->route('rates')
            ->with('success', 'Tarifa creada correctamente');

    }

    private function getNumDays($diasInput)
    {
        $mapa = [];
        if (in_array('lunes-viernes', $diasInput)) {
            $mapa = array_merge($mapa, [1, 2, 3, 4, 5]);
        }
        if (in_array('sabado', $diasInput)) $mapa[] = 6;
        if (in_array('domingo', $diasInput)) $mapa[] = 7;

        return array_unique($mapa);
    }

    public function delete()
    {

    }
    
}
