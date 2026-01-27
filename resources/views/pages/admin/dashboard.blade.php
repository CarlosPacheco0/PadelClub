@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <main class="admin-content">

        <!-- HEADER -->
        <section class="dashboard-header">
            <div>
                <h1><i class="fa-solid fa-chart-line"></i> Dashboard</h1>
                <p>Resumen general del club</p>
            </div>
        </section>

        <!-- KPIs -->
        <section class="kpi-grid">

            <div class="kpi-card green">
                <div>
                    <span>Ingresos Hoy</span>
                    <h2>$120</h2> <!-- QUEMADO -->
                </div>
                <i class="fa-solid fa-dollar-sign"></i>
            </div>

            <div class="kpi-card blue">
                <div>
                    <span>Total Reservas</span>
                    <h2>{{ $infoReservations['amount'] }}</h2>
                </div>
                <i class="fa-solid fa-calendar-check"></i>
            </div>

            <div class="kpi-card purple">
                <div>
                    <span>Canchas Activas</span>
                    <h2>{{ $amountFields }}</h2>
                </div>
                <i class="fa-solid fa-table-tennis-paddle-ball"></i>
            </div>

            <div class="kpi-card yellow">
                <div>
                    <span>Usuarios Registrados</span>
                    <h2>{{ $amountUsers }}</h2>
                </div>
                <i class="fa-solid fa-users"></i>
            </div>

        </section>

        <!-- CHART -->
        <section class="card">
            <div class="chart-header">
                <h2><i class="fa-solid fa-chart-area"></i> Ingresos</h2>
                <div class="chart-filters">
                    <button class="filter-btn active">Día</button>
                    <button class="filter-btn">Semana</button>
                    <button class="filter-btn">Mes</button>
                </div>
            </div>

            <canvas height="120"></canvas> <!-- GRÁFICA QUEMADA -->
        </section>

        <!-- RESERVAS -->
        <section class="card">
            <h2><i class="fa-solid fa-list"></i> Reservas recientes</h2>

            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Cancha</th>
                        <th>Fecha</th>
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
                                {{ $reservation->schedule->start_time->format('H:i') }}
                                -
                                {{ $reservation->schedule->end_time->format('H:i') }}
                            </td>
                            <td>
                                <span class="status {{ $reservation->status_reservation }}">
                                    {{ ucfirst($reservation->status_reservation) }}
                                </span>
                            </td>
                            <td>{{ $reservation->observation ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-data">
                                No hay registros para mostrar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const ctx = document.querySelector('canvas').getContext('2d');

            const datasets = {
                day: {
                    labels: ['8am', '10am', '12pm', '2pm', '4pm', '6pm', '8pm'],
                    data: [20, 40, 60, 50, 80, 70, 120]
                },
                week: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    data: [300, 450, 500, 380, 700, 850, 620]
                },
                month: {
                    labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
                    data: [1800, 2400, 2100, 3200]
                }
            };

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: datasets.day.labels,
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: datasets.day.data,
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 4,
                        backgroundColor: 'rgba(34,197,94,0.15)',
                        borderColor: '#22c55e'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '$' + value
                            }
                        }
                    }
                }
            });

            /* FILTROS */
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', () => {

                    document.querySelectorAll('.filter-btn')
                        .forEach(b => b.classList.remove('active'));

                    btn.classList.add('active');

                    const type = btn.textContent.toLowerCase();

                    chart.data.labels = datasets[type].labels;
                    chart.data.datasets[0].data = datasets[type].data;
                    chart.update();
                });
            });

        });
    </script>
@endsection
