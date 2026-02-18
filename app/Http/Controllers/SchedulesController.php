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
        // $schedules = Schedule::orderBy('id', 'asc')
        //     ->get();

        return view('pages.admin.scheduleAssignment');
    }

    public function getInfoDate(Request $request)
    {
        // Horarios disponible
        // $allSchedules = Schedule::where('status', 1)
        //     ->orderBy('id', 'asc')
        //     ->get();


        /// Horarios asignados con fecha
        $schedulesAssigned = ScheduleDate::with('schedule:id,start_time,end_time')
            ->where('date', $request->date)
            ->orderBy('id', 'asc')
            ->get();

        // IDs de horarios asignados
        $schedulesAddedIds = $schedulesAssigned->pluck('schedule_id');

        // Horarios disponibles
        $schedulesFree = Schedule::select(
            'id',
            'start_time',
            'end_time',
            'status'
        )
            ->where('status', '1')
            ->whereNotIn('id', $schedulesAddedIds)
            ->orderBy('id', 'asc')
            ->get();

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

        // Recorremos cada valor obtenido para la inserción
        foreach ($validated['dates'] as $date) {
            foreach ($validated['schedules'] as $scheduleId) {
                $data[] = [
                    'schedule_id' => $scheduleId,
                    'date'        => $date,
                    'created_at'  => now()
                ];
            }
        }

        // Realizar insert de la data
        $insertedCount = DB::table('schedule_dates')->insertOrIgnore($data);

        if ($insertedCount > 0) {
            return response()->json([
                'status'  => 'success',
                'title'   => 'Completado',
                'message' => 'Horarios asignados correctamente.'
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
            'schedules.*'   => 'integer|exists:schedules,id',
        ]);


        // Buscar horarios correspondientes por fecha
        $delete = ScheduleDate::where('date', $validated['date'])
            ->whereIn('id', $validated['schedules'])
            ->delete();

        return response()->json([
            'message' => 'Horarios eliminados correctamente'
        ]);
    }
}
