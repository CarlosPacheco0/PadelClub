@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <main class="admin-content">

        <h1 class="content-title">Dashboard</h1>
        <h2 class="content-title">Reservas recientes</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Cancha</th>
                    <th>Fecha de reserva</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($topReservations as $reservation)
                    <tr>
                        <td>{{ $reservation->user->name }}</td>
                        <td>{{ $reservation->field->name }}</td>
                        <td>{{ $reservation->date->format('d-m-Y') }}</td>
                        <td>
                            {{ $reservation->schedule->start_time->format('H:i') . ' - ' . $reservation->schedule->end_time->format('H:i') }}
                        </td>
                        <td>{{ $reservation->status_reservation }}</td>
                        <td>{{ $reservation->observation }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-data">No hay registros para mostrar</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <h2 class="content-title">Estadísticas rápidas</h2>
        <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-top:20px;">
            <div class="card">
                <h3>Total Reservas</h3>
                <p>{{ $infoReservations['amount'] }}</p>
            </div>
            <div class="card">
                <h3>Canchas Activas</h3>
                <p>{{ $amountFields }}</p>
            </div>
            <div class="card">
                <h3>Usuarios Registrados</h3>
                <p>{{ $amountUsers }}</p>
            </div>
        </div>
    </main>
@endsection
