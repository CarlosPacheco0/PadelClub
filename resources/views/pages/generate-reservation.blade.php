@extends('layouts.layout')

{{-- ================= ESTILOS ================= --}}
@push('styles')
    @vite(['resources/css/layout.css', 'resources/css/generate-reservation.css'])
@endpush


{{-- ================= CONTENIDO ================= --}}
@section('content')
    <section class="section">

        <div id="container-header">
            <h2 class="content-title">Realiza tu reserva</h2>
            <p class="text-muted">Selecciona una cancha, una fecha y un horario disponible</p>
        </div>

        <div id="container-section">
            <div id="container-dates">
                {{-- ===== FECHAS Y CALENDARIO ===== --}}
                <div class="date-container">

                    <span class="content-title">Fechas disponibles</span>

                    {{-- Fechas rápidas --}}
                    <div class="dates"></div>

                    {{-- Calendario --}}
                    <div class="calendar-modal">
                        <div class="calendar-card">

                            <button class="close-calendar" aria-label="Cerrar calendario" onclick="closeCalendar()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="m6.4 18.308l-.708-.708l5.6-5.6l-5.6-5.6l.708-.708l5.6 5.6l5.6-5.6l.708.708l-5.6 5.6l5.6 5.6l-.708.708l-5.6-5.6z" />
                                </svg>
                            </button>

                            <div class="calendar-header">
                                <button id="prevBtn" onclick="prevMonth()" class="nav-btn" aria-label="Mes anterior">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M15.41 7.41L14 6l-6 6l6 6l1.41-1.41L10.83 12z" />
                                    </svg>
                                </button>

                                <h3 id="calendarTitle"></h3>

                                <div class="calendar-actions">
                                    <button onclick="goToCurrentMonth()" class="btn-today">Hoy</button>
                                    <button onclick="nextMonth()" class="nav-btn" aria-label="Mes siguiente">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M8.59 16.59L10 18l6-6l-6-6l-1.41 1.41L13.17 12z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="calendar-weekdays">
                                <div>Lun</div>
                                <div>Mar</div>
                                <div>Mié</div>
                                <div>Jue</div>
                                <div>Vie</div>
                                <div>Sáb</div>
                                <div>Dom</div>
                            </div>

                            <div class="calendar-days" id="calendarDays"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="container-schedules">
                {{-- Horarios --}}
                <div id="schedules" class="schedules-grid schedules-empty">
                    <h2 class="content-title">Horarios disponibles</h2>
                    <div id="schedules-list">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                                <path fill="currentColor" opacity="0.3"
                                    d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8z" />
                                <path fill="currentColor" d="M12.5 7H11v6l5.2 3.2l.8-1.3l-4.5-2.7V7z" />
                            </svg>
                            <p>Selecciona una cancha y una fecha para ver los horarios disponibles</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container-fields">
                {{-- ===== CANCHAS ===== --}}
                <div class="container-fields">
                    <span class="content-title">Canchas disponibles</span>
                    <div class="fields-grid"></div>
                </div>
            </div>

        </div>

    </section>
@endsection


{{-- ================= CONFIG + JS ================= --}}
@push('scripts')
    {{-- Configuración para JS --}}
    <script>
        window.RESERVATION_CONFIG = {
            schedulesUrl: "{{ route('schedulesFree') }}",
            reservationUrl: "{{ route('reservation.generate') }}",
            fieldsUrl: "{{ route('fieldsFree') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    @vite(['resources/js/pages/views/generate-reservation.js'])
@endpush
