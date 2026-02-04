<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Importar modelos
use App\Models\Schedule;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Field;
use App\Models\ScheduleDate;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    // Generación de nueva reserva
    public function index()
    {
        $fields = Field::where('status', 1)
            ->orderBy('name', 'desc')
            ->get();

        $schedules = Schedule::all();
        return view('pages.generate-reservation', compact('fields', 'schedules'));
    }

    // Confirmación de la creación de la reserva
    public function generateReservation(Request $request)
    {

        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'schedule_id' => 'required|exists:schedules,id',
            'date'        => 'required|date'
        ]);

        $field = Field::find($request->field_id);
        $schedule = Schedule::find($request->schedule_id);
        $dateReservation = Carbon::parse($request->date);

        // Infor del usuario en sesión
        $user = Auth::user();

        return view('pages.confirm-reservation', compact('field', 'schedule', 'dateReservation', 'user'));
    }

    // Se guarda la reserva en DB
    public function save(Request $request)
    {

        $validated = $request->validate([
            'date'        => 'required|date',
            'field_id'    => 'required|exists:fields,id',
            'schedule_id' => 'required|exists:schedules,id',
            'name'        => 'required|string',
            'observation' => 'nullable|string'
        ]);


        // Validar si el cliente existe.
        $user = User::where('name', $validated['name'])->first();

        // ID del cliente encontrado o registrado
        $userID = $user->id;

        // Registrar reserva
        Reservation::create([
            'field_id' => $validated['field_id'],
            'schedule_id' => $validated['schedule_id'],
            'user_id' => $userID,
            'date' => $validated['date'],
            'observation' => $validated['observation'],
            'status_reservation' => 'pendiente',
        ]);


        // Redireccionar a la pagina principal 
        // luego de la creación
        // return redirect(route('home'));
        return redirect()
            ->route('home')
            ->with('success', 'Reserva creada correctamente');
    }

    // Ver reservas del clieten
    public function reservationsList()
    {

        // Infor del usuario en sesión
        $user = Auth::user();

        // Obtener información de las reservas
        $reservations = Reservation::select(
            'id',
            'field_id',
            'schedule_id',
            'user_id',
            'date',
            'observation',
            'status_reservation'
        )->where('user_id', $user->id)
            ->with([
                'field:id,name',
                'schedule:id,start_time,end_time',
                'user:id,name'
            ])
            ->orderByDesc('id')
            ->get();

        return view('pages.listReservations', compact('reservations'));
    }

    // Actualizar reserva desde el usuario
    public function updateReservation(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'date'           => 'required|date',
            'field_id'       => 'required|exists:reservations,field_id',
            'schedule_id'    => 'required|exists:schedules,id',
            'observation'    => 'nullable|string'
        ]);

        // Buscar la reserva para actualizar
        $reservation = Reservation::findOrFail($request->reservation_id);

        $reservation->update([
            'date'               => $validated['date'],
            'field_id'           => $validated['field_id'],
            'schedule_id'        => $validated['schedule_id'],
            'observation'        => $validated['observation']
        ]);

        return redirect()
            ->route('reservations.list')
            ->with('success', 'Reserva actualizada correctamente');
    }

    // Cancelar reserva
    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:reservations,id'
        ]);

        // Buscar la reserva para eliminar
        $reservation = Reservation::findOrFail($validated['id']);

        $reservation->update([
            'status_reservation' => 'cancelada'
        ]);

        return redirect()
            ->route(($request->flag != 'admin') ? 'reservations.list' : 'reservations')
            ->with('success', 'Reserva cancelada correctamente');
    }

    // Obtener los horarios disponibles por Cancha y Fecha
    public function schedulesFree(Request $request)
    {

        $validated = $request->validate([
            'field_id'  => 'required|exists:fields,id',
            'date'      => 'required|date'
        ]);


        $schedules = $this->getSchedulesFree(
            (int) $validated['field_id'],
            $validated['date']
        );

        return response()->json([
            'success' => true,
            'data'    => $schedules,
        ]);
    }

    /**
     * Obtiene los horarios disponibles y calcula su precio dinámicamente.
     * Refactorizado para alto rendimiento y legibilidad.
     */
    private function getSchedulesFree(int $field_id, string $date): array
    {
        // 1. Configuración inicial
        $carbonDate = \Carbon\Carbon::parse($date);
        $dayWeek = $carbonDate->dayOfWeekIso;

        // 2. Optimización: Pre-procesar tarifas a una estructura ligera (Minutos)
        // Esto evita instanciar Carbon cientos de veces dentro del bucle de horarios.
        $rates = \App\Models\Rate::where('day_week', $dayWeek)
            ->get()
            ->map(function ($rate) {
                // Asumimos que start_time/end_time son Carbon o Strings H:i:s
                $start = \Carbon\Carbon::parse($rate->start_time);
                $end = \Carbon\Carbon::parse($rate->end_time);
                return (object) [
                    'start_min' => ($start->hour * 60) + $start->minute,
                    'end_min'   => ($end->hour * 60) + $end->minute,
                    'price'     => (float) $rate->price,
                ];
            });

        // 3. Obtener IDs necesarios (Assignments y Reservations)
        $assignedScheduleIds = ScheduleDate::where('date', $date)
            ->pluck('schedule_id'); // No necesitamos toArray aquí, whereIn acepta colecciones

        $reservedScheduleIds = Reservation::where('date', $date)
            ->where('field_id', $field_id)
            ->pluck('schedule_id')
            ->toArray(); // Aquí sí array para búsquedas rápidas con in_array

        // 4. Procesamiento Core: Fetch -> Map (Precio) -> Filter (Reservas)
        return Schedule::whereIn('id', $assignedScheduleIds)
            ->get()
            ->map(function ($schedule) use ($rates) {
                // Cálculo de precio optimizado
                $price = $this->calculatePriceForSchedule($schedule, $rates);

                return [
                    'id'    => $schedule->id,
                    'hour'  => $schedule->start_time->format('H:i') . ' - ' . $schedule->end_time->format('H:i'),
                    'price' => $price,
                ];
            })
            // Filtramos los horarios que ya están en la lista de reservas
            ->filter(fn($item) => !in_array($item['id'], $reservedScheduleIds))
            ->values()
            ->toArray();
    }

    /**
     * Calcula el precio basado en intersección de tiempos (Weighted Pricing).
     * Trabaja puramente con minutos (Integers) para máxima velocidad.
     * * @param \App\Models\Schedule $schedule
     * @param \Illuminate\Support\Collection $normalizedRates Colección de tarifas pre-procesadas
     */
    private function calculatePriceForSchedule($schedule, $normalizedRates): float
    {
        // Convertir horario a minutos
        $startMin = ($schedule->start_time->hour * 60) + $schedule->start_time->minute;
        $endMin   = ($schedule->end_time->hour * 60) + $schedule->end_time->minute;
        $duration = $endMin - $startMin;

        if ($duration <= 0) return 0.00;

        $totalCost = 0.0;
        $minutesCovered = 0;

        foreach ($normalizedRates as $rate) {
            // Lógica de intersección matemática simple (sin Carbon)
            $overlapStart = max($startMin, $rate->start_min);
            $overlapEnd   = min($endMin, $rate->end_min);
            $overlap      = $overlapEnd - $overlapStart;

            if ($overlap > 0) {
                // Proporción del tiempo cubierto
                $ratio = $overlap / $duration;
                $totalCost += ($rate->price * $ratio);
                $minutesCovered += $overlap;
            }
        }

        // Si no hay cobertura de tarifa, retornamos 0
        if ($minutesCovered === 0) return 0.00;

        return round($totalCost, 2);
    }

    // Gestión de todas las reservas (Vista principal)
    public function managementReservation()
    {
        // Obtener información de las reservas
        $reservations = Reservation::select(
            'id',
            'field_id',
            'schedule_id',
            'user_id',
            'date',
            'observation',
            'status_reservation'
        )
            ->with([
                'field:id,name',
                'schedule:id,start_time,end_time',
                'user:id,name'
            ])
            ->orderByDesc('id')
            ->get();

        return view('pages.admin.reservations', compact('reservations'));
    }

    // Obtener las canchas disponibles
    public function fieldsFree(Request $request)
    {
        $validated = $request->validate([
            'field_id'  => 'required|exists:fields,id',
            'date'      => 'required|date'
        ]);


        // Obtener las canchas disponibles ordenadas por nombre
        $fieldsFree = Field::select('id', 'name')
            ->where('status', 1)
            ->orderBy('name', 'desc')
            ->get();

        // Obtener los horarios disponibles para la cancha y fecha actual
        $schedules = $this->getSchedulesFree(
            (int) $validated['field_id'],
            $validated['date']
        );

        return [
            'fields' => $fieldsFree,
            'schedules' => $schedules
        ];
    }

    // Actualizar una reserva generada desde Administrador
    public function update(Request $request)
    {

        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'date'           => 'required|date',
            'field_id'       => 'required|exists:reservations,field_id',
            'schedule_id'    => 'required|exists:schedules,id',
            'status'         => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        // Buscar la reserva para actualizar
        $reservation = Reservation::findOrFail($request->reservation_id);

        $reservation->update([
            'date'               => $request->date,
            'field_id'           => $request->field_id,
            'schedule_id'        => $request->schedule_id,
            'status_reservation' => $request->status,
        ]);

        return redirect(route('reservations'));
    }

    // Eliminar registro (Reserva)
    public function delete(Request $request)
    {
        $reservation = Reservation::where('id', $request->id)->first();

        if ($reservation) {
            $reservation->delete();
        }
        return redirect(route('reservations'));
    }
}
