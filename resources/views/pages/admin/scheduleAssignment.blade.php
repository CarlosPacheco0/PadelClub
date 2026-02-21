@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/schedule_assignment.css'])
@endpush

@section('content')
    <div class="page-container">

        <div class="header-section">
            <h2 class="page-title">Gestión de Horarios</h2>
            <p class="page-subtitle">Selecciona una fecha del calendario para asignar o eliminar disponibilidad.</p>
        </div>

        <div class="assignment-layout">

            <div class="left-column">
                <div class="calendar-card">
                    <div class="calendar-header">
                        <button id="prevBtn" onclick="prevMonth()" class="nav-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>

                        <h3 id="calendarTitle"></h3>

                        <div class="calendar-actions">
                            <button onclick="goToCurrentMonth()" class="btn-today">Hoy</button>
                            <button onclick="nextMonth()" class="nav-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6" />
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

            <div class="right-column">

                <div class="card action-card">
                    <div class="card-header">
                        <div class="icon-box add">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Asignar Nuevos Horarios</h3>
                            <span class="card-desc">Marca los cupos que deseas habilitar</span>
                        </div>
                    </div>

                    <div class="schedule-container">
                        <div class="schedule-list free">
                            <div class="empty-state">
                                <span>Selecciona una fecha primero</span>
                            </div>
                        </div>
                    </div>

                    <div class="action-footer">
                        <div class="summary-pill">
                            <span class="dot"></span> Seleccionados: <strong class="schedules-selecteds"
                                style="margin-left: 4px;">0</strong>
                        </div>
                        <button class="btn btn-primary btn-disabled" id="save-btn" onclick="saveData()">
                            Guardar Asignación
                        </button>
                    </div>
                </div>

                <div class="card action-card">
                    <div class="card-header">
                        <div class="icon-box delete">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Horarios Registrados</h3>
                            <span class="card-desc">Marca los cupos para eliminarlos</span>
                        </div>
                    </div>

                    <div class="schedule-container">
                        <div class="schedule-list added">
                            <div class="empty-state">
                                <span>Selecciona una fecha primero</span>
                            </div>
                        </div>
                    </div>

                    <div class="action-footer">
                        <div style="flex-grow:1"></div> 
                        <button type="button" class="btn btn-delete btn-disabled" id="delete-btn" onclick="deleteSchedules()">
                            Eliminar Seleccionados
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= CONFIG + JS ================= --}}
    @push('scripts')
        {{-- Configuración para JS --}}
        <script>
            window.SCHEDULE_CONFIG = {
                url_store: "{{ route('assignment.store') }}",
                url_get_info: "{{ route('assignment.info') }}",
                url_delete: "{{ route('assignment.delete') }}"
            };
        </script>

        @vite(['resources/js/pages/views/schedule-assignment.js'])
    @endpush

  
@endsection
