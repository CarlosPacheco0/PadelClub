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
        $request->validate([
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price' => 'required|numeric|min:0',
            'force_replace' => 'boolean' // Campo bandera para confirmar sustitución
        ]);

        $daysSelected = $this->getNumDays($request->days);
        $newStart = Carbon::parse($request->start_time);
        $newEnd = Carbon::parse($request->end_time);

        // Array para recolectar conflictos y devolverlos al frontend si es necesario
        $conflicts = [];
        $ratesToReplace = [];

        // PASO 1: ANÁLISIS DE CONFLICTOS
        foreach ($daysSelected as $dayNum) {
            // Buscamos tarifas que tengan CUALQUIER intersección temporal ese día
            $overlappingRates = Rate::where('day_week', $dayNum)
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                })
                ->get();

            foreach ($overlappingRates as $existingRate) {
                // Convertimos a Carbon para comparar fácilmente
                $exStart = Carbon::parse($existingRate->start_time);
                $exEnd = Carbon::parse($existingRate->end_time);

                // CASO A: ¿La nueva tarifa ENVUELVE a la existente? (Sustitución)
                // Condición: Nueva empieza antes/igual Y Nueva termina después/igual
                $isCovering = $newStart->lte($exStart) && $newEnd->gte($exEnd);

                if ($isCovering) {
                    $ratesToReplace[] = $existingRate->id;
                    $conflicts['replace'][] = [
                        'day' => $dayNum,
                        'rate' => $existingRate->start_time . ' - ' . $existingRate->end_time
                    ];
                } else {
                    // CASO B: Intersección parcial o "Inicio dentro del rango" (Bloqueante)
                    // Esto incluye si la nueva está DENTRO de la vieja, o si se cruzan parcialmente.
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No es posible crear la tarifa.',
                        'error_detail' => "El horario {$request->start_time} - {$request->end_time} choca parcialmente con la tarifa existente de {$existingRate->start_time} a {$existingRate->end_time} del día {$this->getDayName($dayNum)}."
                    ], 422);
                }
            }
        }

        // PASO 2: DECISIÓN DE SUSTITUCIÓN
        if (!empty($ratesToReplace)) {
            // Si no nos enviaron la confirmación explícita (force_replace), devolvemos Alerta
            if (!$request->boolean('force_replace')) {
                return response()->json([
                    'status' => 'confirm_required',
                    'message' => 'Existe un conflicto de horarios que se puede sustituir.',
                    'details' => $conflicts['replace'],
                    'affected_count' => count($ratesToReplace)
                ], 409); // 409 Conflict
            }
        }

        // PASO 3: EJECUCIÓN (Transacción atómica para seguridad)
        DB::transaction(function () use ($daysSelected, $request, $ratesToReplace) {
            // 1. Si hay confirmación, borramos las viejas
            if (!empty($ratesToReplace)) {
                Rate::whereIn('id', $ratesToReplace)->delete();
            }

            // 2. Creamos las nuevas (Usamos tu lógica de bucle o creación directa según prefieras)
            // Aquí uso la lógica simple de crear el rango solicitado
            foreach ($daysSelected as $dayNum) {
                // Nota: Aquí podrías incluir la lógica de desglose por horas si decides usarla
                Rate::create([
                    'day_week' => $dayNum,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'price' => $request->price
                ]);
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Tarifas guardadas correctamente.'
        ]);
    }

    // Helper para nombre de día
    private function getDayName($num)
    {
        $days = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
        return $days[$num] ?? 'Día desconocido';
    }

    private function getNumDays($daysInput)
    {
        // ... Tu lógica existente de transformación de días ...
        $mapa = [];
        if (in_array('lunes-viernes', $daysInput)) $mapa = array_merge($mapa, [1, 2, 3, 4, 5]);
        if (in_array('sabado', $daysInput)) $mapa[] = 6;
        if (in_array('domingo', $daysInput)) $mapa[] = 7;
        return array_unique($mapa);
    }

    public function delete() {}
}
