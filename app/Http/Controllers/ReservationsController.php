<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Importar modelos
use App\Models\Schedule;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Field;
use App\Models\Rate;
use App\Models\ScheduleDate;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    // Generación de nueva reserva
    public function index()
    {
        return view('pages.generate-reservation');
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

        // Buscar en BD las tarifas que cubren este rango de horario
        $date = $request->date;
        $start_time = $schedule->start_time->format('H:i');
        $end_time = $schedule->end_time->format('H:i');

        $dayWeek = Carbon::parse($dateReservation)->dayOfWeekIso;

        $ratesDB = Rate::where('day_of_week', $dayWeek)
            ->where('start_time', '<', $end_time)  // La tarifa empieza antes de que acabe el turno
            ->where('end_time', '>', $start_time)  // La tarifa termina después de que empiece el turno
            ->take(1)
            ->get('price');

        $rate = $ratesDB->first()['price'];

        return view('pages.confirm-reservation', compact('field', 'schedule', 'dateReservation', 'user', 'rate'));
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
            // 'field_id'  => 'required|exists:fields,id',
            'date'      => 'required|date'
        ]);

        $schedules = $this->getSchedulesFree(
            // (int) $validated['field_id'],
            $validated['date']
        );

        return response()->json([
            'success' => true,
            'data'    => $schedules,
        ]);
    }

    // private function getSchedulesFree(int $field_id, string $date)
    private function getSchedulesFree(string $date)
    {
        // Obtener horarios ya asignados
        $assignedSchedules = ScheduleDate::where('date', $date)
            ->pluck('schedule_id')
            ->toArray();

        // Todos los horarios asignados
        $schedules = Schedule::whereIn('id', $assignedSchedules)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id'   => $schedule->id,
                    'hour' => $schedule->start_time->format('H:i')
                        . ' - ' .
                        $schedule->end_time->format('H:i'),
                ];
            })
            ->values()
            ->toArray();



        // Horarios ya reservados
        $reservations = Reservation::where('date', $date)
            // ->where('field_id', $field_id)
            ->pluck('schedule_id')
            ->toArray();

        // Horarios disponibles
        return array_values(
            array_filter($schedules, function ($schedule) use ($reservations) {
                return !in_array($schedule['id'], $reservations);
            })
        );
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

    // Obtener las canchas y horarios disponibles
    public function fieldsFree(Request $request)
    {
        // 1. VALIDACIÓN FRONTEND (Tu código optimizado)
        if (!$request->field_id) {
            return back()
                ->with('info', 'No se especificó una cancha para la búsqueda.')
                ->withInput();
        }

        if (!$request->date) {
            return back()
                ->with('info', 'Debes seleccionar una fecha válida.')
                ->withInput();
        }

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

        // 1. Buscamos la reserva directamente. Si no existe, devuelve null.
        $reservation = Reservation::find($request->reservation_id);

        if (!$reservation) {
            return back()->with('error', 'Error: La reserva que intentas editar no existe.')->withInput();
        }

        // 2. La fecha (en memoria, no toca la base de datos)
        if (blank($request->date)) {
            return back()->with('info', 'La fecha de la reserva es obligatoria.')->withInput();
        }

        // 3. Validar Cancha y Horario (Estos sí requieren exists porque no los vamos a editar)
        if (!Field::where('id', $request->field_id)->exists()) {
            return back()->with('error', 'La cancha seleccionada no existe.')->withInput();
        }

        if (!Schedule::where('id', $request->schedule_id)->exists()) {
            return back()->with('error', 'El horario seleccionado no es válido o no existe.')->withInput();
        }


        // 1. Validaciones básicas y correcciones
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'date'           => 'required|date',
            // CORRECCIÓN: Antes validabas contra 'reservations', debe ser contra 'fields'
            'field_id'       => 'required|exists:fields,id',
            'schedule_id'    => 'required|exists:schedules,id',
            'status'         => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        // 2. Regla de Negocio: Evitar "Choque de Horarios"
        // Solo verificamos si la reserva NO se está cancelando
        if (in_array($validated['status'], ['pendiente', 'confirmada'])) {
            $horarioOcupado = Reservation::where('date', $validated['date'])
                ->where('field_id', $validated['field_id'])
                ->where('schedule_id', $validated['schedule_id'])
                ->where('id', '!=', $validated['reservation_id']) // Excluimos esta misma reserva
                ->whereIn('status_reservation', ['pendiente', 'confirmada']) // Ignoramos las canceladas
                ->exists();

            if ($horarioOcupado) {
                // Si hay choque, devolvemos al usuario con un error rojo
                return back()
                    ->with('error', 'El horario seleccionado ya está ocupado para esta cancha en esa fecha.')
                    ->withInput();
            }
        }

        // 3. Proceso de Actualización
        $reservation = Reservation::findOrFail($validated['reservation_id']);

        $reservation->update([
            'date'               => $validated['date'],
            'field_id'           => $validated['field_id'],
            'schedule_id'        => $validated['schedule_id'],
            'status_reservation' => $validated['status'],
        ]);

        // 4. Redirección con Mensaje de Éxito
        return redirect(route('reservations'))
            ->with('success', 'Reserva actualizada correctamente.');
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
