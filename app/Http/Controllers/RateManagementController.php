<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

        // blank() detecta: null, [], "", o espacios en blanco.
        if (blank($request->days)) {
            return back()
                ->with('info', "Debes seleccionar al menos un día de la semana.")
                ->withInput();
        }

        // 1. Validación de existencia de horas (¿Llegaron los datos?)
        if (blank($request->start_time) || blank($request->end_time)) {
            return back()
                ->with('info', 'Por favor selecciona una hora de Inicio y Fin.')
                ->withInput();
        }

        // Validar que la hora inicio no sea mayor a la hora fin
        if ( $request->start_time >= $request->end_time ) {
            return back()
                ->with('error', 'Error', 'La hora de inicio (' + $request->start_time +
                    ') no puede ser mayor o igual a la hora final (' + $request->end_time + ').')
                ->withInput();
        }
       

        // Validamos si no existe, está vacío o no es un número mayor a cero
        if (blank($request->price) || !is_numeric($request->price) || $request->price <= 0) {
            return back()
                ->with('info', "Debes ingresar un precio válido mayor a 0.")
                ->withInput();
        }

        $validated = $request->validate([
            'days'       => 'required|array|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'price'      => 'required|numeric|min:0',
        ]);

        $start = $validated['start_time'];
        $end   = $validated['end_time'];
        $price = $validated['price'];
        $days  = $validated['days'];

        $conflicts = [];
        $createdCount = 0;

        // 2. Procesar cada día seleccionado
        foreach ($days as $day) {

            // LÓGICA DE VALIDACIÓN DE SUPERPOSICIÓN (SIMPLIFICADA)
            // La regla de oro: Una fecha se solapa si:
            // (Inicio Existente < Fin Nuevo) Y (Fin Existente > Inicio Nuevo)

            $conflicto = Rate::where('day_of_week', $day)
                ->where(function ($query) use ($start, $end) {
                    $query->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                })
                ->first(); // Traemos el registro culpable si existe

            if ($conflicto) {
                // Si hay conflicto, guardamos el nombre del día para avisar
                $daysNames = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];
                // Opcional: Podrías guardar más detalles como: "Lun (choca con 08:00-10:00)"
                $conflicts[] = $daysNames[$day];
            } else {
                // ✅ Si no hay conflicto, creamos la tarifa
                Rate::create([
                    'day_of_week' => $day,
                    'start_time'  => $start,
                    'end_time'    => $end,
                    'price'       => $price
                ]);
                $createdCount++;
            }
        }

        // 3. Respuesta al Usuario
        if (count($conflicts) > 0) {
            $daysString = implode(', ', $conflicts);

            // Mensaje inteligente: Si se crearon algunas pero otras fallaron
            if ($createdCount > 0) {
                return back()
                    ->with('info', "Se guardaron algunas tarifas, pero NO se pudieron crear para: $daysString porque chocan con horarios existentes.")
                    ->withInput();
            }

            return back()
                ->with('error', "No se pudo crear ninguna tarifa. Los días: $daysString tienen conflictos de horario.")
                ->withInput();
        }

        return back()
            ->with('success', '¡Tarifas configuradas correctamente para todos los días seleccionados!')
            ->withInput();
    }

    public function edit(Request $request)
    {

        if ( blank($request->id) || !is_numeric($request->id) ) 
        {
            return back()
                ->with('error', "Error critico: No se identifico la tarifa.")
                ->withInput();
        }

        // Validamos si no existe, está vacío o no es un número mayor a cero
        if (blank($request->price) || !is_numeric($request->price) || $request->price <= 0) 
        {
            return back()
                ->with('info', "Debes ingresar un precio válido mayor a 0.")
                ->withInput();
        }

        // 1. Validar solo el precio (que viene del input hidden 'price')
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        // 2. Buscar la tarifa manualmente por el ID que llega en la URL
        $rate = Rate::findOrFail($request->id); // Si no existe, lanza error 404 automáticamente

        if ($rate) {
            // 3. Actualizar el registro
            $rate->price = $validated['price'];
            $rate->save();

            // 4. Retornar con mensaje de éxito
            return back()->with('success', 'Precio actualizado correctamente.');
        }

        return back()->with('error', 'No fue posible actualizar el precio');
    }
}
