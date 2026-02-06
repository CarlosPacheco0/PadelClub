@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <main class="admin-content">

        <section class="dashboard-header">
            <div class="header-title">
                <h1>Hola, Admin 👋</h1>
                <p>Aquí tienes el resumen de actividad de <strong>Pádel Club</strong></p>
            </div>

            <div class="date-badge">
                <i class="fa-regular fa-calendar"></i>
                {{ now()->format('d M, Y') }}
            </div>
        </section>

        <section class="kpi-grid">

            <div class="kpi-card revenue">
                <div class="kpi-info">
                    <span>Ingresos Hoy</span>
                    <h2>$120</h2>
                </div>
                <div class="kpi-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
            </div>

            <div class="kpi-card reservations">
                <div class="kpi-info">
                    <span>Total Reservas</span>
                    <h2>{{ $infoReservations['amount'] }}</h2>
                </div>
                <div class="kpi-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>

            <div class="kpi-card fields">
                <div class="kpi-info">
                    <span>Canchas Activas</span>
                    <h2>{{ $amountFields }}</h2>
                </div>
                <div class="kpi-icon">
                    <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                </div>
            </div>

            <div class="kpi-card users">
                <div class="kpi-info">
                    <span>Usuarios</span>
                    <h2>{{ $amountUsers }}</h2>
                </div>
                <div class="kpi-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

        </section>

        <section class="card">
            <div class="chart-header">
                <h2 class="section-title">
                    <i class="fa-solid fa-chart-area" style="color: var(--primary)"></i>
                    Balance de Ingresos
                </h2>
                <div class="chart-filters">
                    <button class="filter-btn active">Día</button>
                    <button class="filter-btn">Semana</button>
                    <button class="filter-btn">Mes</button>
                </div>
            </div>

            <div style="position: relative; width: 100%;">
                <canvas id="incomeChart" height="100"></canvas>
            </div>
        </section>

        <section class="card">
            <div class="chart-header">
                <h2 class="section-title">
                    <i class="fa-solid fa-list-check" style="color: var(--primary)"></i>
                    Últimas Reservas
                </h2>
                <a href="#"
                    style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Ver todas
                    &rarr;</a>
            </div>

            <div class="table-container">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Cancha</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topReservations as $reservation)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-small">
                                            {{ strtoupper(substr($reservation->user->name, 0, 1)) }}
                                        </div>
                                        {{ $reservation->user->name }}
                                    </div>
                                </td>
                                <td>{{ $reservation->field->name }}</td>
                                <td>
                                    <div style="display: flex; flex-direction: column; line-height: 1.2;">
                                        <span style="font-weight: 600;">{{ $reservation->date->format('d/m') }}</span>
                                        <span style="font-size: 0.8rem; color: #64748b;">
                                            {{ $reservation->schedule->start_time->format('H:i') }} -
                                            {{ $reservation->schedule->end_time->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status {{ strtolower($reservation->status_reservation) }}">
                                        {{ ucfirst($reservation->status_reservation) }}
                                    </span>
                                </td>
                                <td style="color: #64748b; font-size: 0.85rem;">
                                    {{ Str::limit($reservation->observation ?? '-', 30) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-data">
                                    <i class="fa-regular fa-folder-open"
                                        style="font-size: 24px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                                    No hay reservas recientes
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
