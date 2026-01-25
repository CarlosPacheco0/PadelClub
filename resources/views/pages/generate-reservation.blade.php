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
                {{-- ===== CANCHAS ===== --}}
                <div class="container-fields">
                    <span class="content-title">Canchas disponibles</span>
                    <div class="fields-grid">
                        @foreach ($fields as $field)
                            <div class="field-card" data-id="{{ $field->id }}" onclick="selectField(this)">

                                <div class="field-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 512 512">
                                        <path fill="#0b3c5d"
                                            d="M120.8 55L87.58 199h18.52l29.1-126h18.2l-20.6 126h18.3l10.1-62H247v62h18v-62h85.8l10.1 62h18.3L358.6 73h18.2l29.1 126h18.5L391.2 55zm50.9 18h168.6l7.6 46H164.1zM73 217v30h366v-30zm-.64 48L20.69 489H491.3l-51.7-224h-18.5l47.6 206h-45L390 265h-18.3l14.2 87H265v-87h-18v87H126.1l14.2-87H122L88.35 471H43.31l47.56-206zm50.74 105h265.8l16.5 101H106.6z" />
                                    </svg>
                                </div>

                                <div class="field-info">
                                    <h3>{{ $field->name }}</h3>
                                    <p>{{ $field->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== FECHAS Y CALENDARIO ===== --}}
                <div class="date-container">

                    <span class="content-title">Fechas disponibles</span>

                    {{-- Fechas rápidas --}}
                    <div class="dates"></div>

                    {{-- Calendario --}}
                    <div class="calendar-modal">
                        <div class="calendar-card">

                            <button class="close-calendar" aria-label="Cerrar calendario" onclick="closeCalendar()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                                    <path fill="#666666"
                                        d="m6.4 18.308l-.708-.708l5.6-5.6l-5.6-5.6l.708-.708l5.6 5.6l5.6-5.6l.708.708l-5.6 5.6l5.6 5.6l-.708.708l-5.6-5.6z" />
                                </svg>
                            </button>

                            <div class="calendar-header">
                                <button id="prevBtn" onclick="prevMonth()" class="button-action">‹</button>

                                <h3 id="calendarTitle"></h3>

                                <div class="calendar-actions">
                                    <button onclick="goToCurrentMonth()" class="btn-today">Hoy</button>
                                    <button onclick="nextMonth()" class="button-action">›</button>
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
                <div id="schedules" class="schedules-grid schedules-empty ">
                    <h2 class="content-title">Horarios disponibles</h2>
                    <div id="schedules-list">No hay canchas o fechas selecionadas</div>
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
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    @vite(['resources/js/pages/views/generate-reservation.js'])
@endpush
