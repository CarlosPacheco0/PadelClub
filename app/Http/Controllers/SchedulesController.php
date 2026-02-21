<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduleDate;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{
    public function __invoke()
    {
        $schedules = Schedule::orderBy('id', 'asc')
            ->get();

        return view('pages.admin.schedules', compact('schedules'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'start'  => 'required|date_format:H:i',
            'end'    => 'required|date_format:H:i|after:start',
            'status' => 'required|boolean'
        ]);

        // Validar existencia
        $exists = Schedule::where('start_time', $validated['start'])
            ->where('end_time', $validated['end'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'start' => 'El horario se cruza con otro existente para esta fecha'
            ])->withInput();
        }

        Schedule::create([
            'start_time'  => $validated['start'],
            'end_time' => $validated['end'],
            'status' => $validated['status'],
        ]);

        return redirect(route('schedules'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'start'  => 'required|date_format:H:i',
            'end'    => 'required|date_format:H:i|after:start',
            'status' => 'required|boolean'
        ]);

        // Validar existencia
        $exists = Schedule::where('start_time', $validated['start'])
            ->where('end_time', $validated['end'])
            ->where('id', '!=', $request->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'start' => 'El horario se cruza con otro existente para esta fecha'
            ])->withInput();
        }

        $schedule = Schedule::findOrFail($request->id);

        $schedule->update([
            'start_time' => $validated['start'],
            'end_time' => $validated['end'],
            'status' => $validated['status']
        ]);

        return redirect(route('schedules'));
    }

    public function delete(Request $request)
    {
        $schedule = Schedule::where('id', $request->id)->first();

        if ($schedule) {
            $schedule->delete();
        }

        return redirect(route('schedules'));
    }

    // Asignación de horarios
    public function assignment()
    {
        return view('pages.admin.scheduleAssignment');
    }

    public function getInfoDate(Request $request)
    {

        // Validamos que sea una fecha o un array de fechas
        $dates = is_array($request->date) ? $request->date : [$request->date];

        // Si hay más de una fecha, listamos todos los horarios sin filtrar disponibilidad
        if (count($dates) > 1) {
            $allSchedules = Schedule::where('status', '1')
                ->orderBy('id', 'asc')
                ->get(['id', 'start_time', 'end_time', 'status']);

            return response()->json([
                'free'  => $allSchedules
            ]);
        }

        // Lógica para una sola fecha
        /// Horarios asignados con fecha
        $schedulesAssigned = ScheduleDate::with('schedule:id,start_time,end_time')
            ->where('date', $request->date)
            ->orderBy('id', 'asc')
            ->get();

        // IDs de horarios asignados
        $schedulesAddedIds = $schedulesAssigned->pluck('schedule_id');

        // Horarios disponibles
        $schedulesFree = Schedule::where('status', '1')
            ->whereNotIn('id', $schedulesAddedIds)
            ->orderBy('id', 'asc')
            ->get(['id', 'start_time', 'end_time', 'status']);

        return response()->json([
            'free'  => $schedulesFree,
            'added' => $schedulesAssigned
        ]);
    }

    // Guardar horarios seleccionados por fecha
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date',
            'schedules' => 'required|array|min:1',
            'schedules.*' => 'required|integer|exists:schedules,id',
        ]);

        $data = [];
        $now = now();

        // Recorremos cada valor obtenido para la inserción
        foreach ($validated['dates'] as $date) {
            foreach ($validated['schedules'] as $scheduleId) {
                $data[] = [
                    'schedule_id' => $scheduleId,
                    'date'        => $date,
                    'created_at'  => $now
                ];
            }
        }

        // Realizar insert de la data
        $insertedCount = DB::table('schedule_dates')->insertOrIgnore($data);

        $totalEnviados = count($data);

        if ($insertedCount > 0) {
            // Si se insertaron menos de los enviados, informamos que algunos se omitieron
            $mensaje = ($insertedCount < $totalEnviados)
                ? "Se asignaron $insertedCount horarios nuevos. Los horarios que ya estaban ocupados fueron omitidos."
                : "Horarios asignados correctamente.";

            return response()->json([
                'status'  => 'success',
                'title'   => 'Completado',
                'message' => $mensaje
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'title'   => 'Error',
            'message' => 'No fue posible asignar los horarios.'
        ]);
    }

    public function assignmentDelete(Request $request)
    {
        $validated = $request->validate([
            'date'          => 'required|date',
            'schedules'     => 'required|array|min:1',
            'schedules.*'   => 'integer|exists:schedule_dates,id',
        ]);

        // return $validated['date'];  

        // Buscar horarios correspondientes por fecha
        $deleted = ScheduleDate::where('date', $validated['date'])
            ->whereIn('id', $validated['schedules'])
            ->delete();

        if ($deleted) {
            return response()->json([
                'status'  => 'success',
                'title'   => 'Completado',
                'message' => 'El horario asignado fue eliminado correctamente.'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'title'   => 'Error',
            'message' => 'No fue posible eliminar el horario asignado.'
        ]);
    }
}
