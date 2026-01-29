<x-nav-link route="dashboard" label="Dashboard" />
<x-nav-link route="fields" label="Canchas" />
<x-nav-link route="reservations" label="Reservas" />

@php
    $horariosActive = request()->routeIs('schedules*', 'schedule.assignment');
@endphp

<div class="nav-dropdown">
    <button class="nav-trigger {{ $horariosActive ? 'active' : '' }}">
        Horarios <span class="caret">▾</span>
    </button>

    <div class="nav-menu">
        <a href="{{ route('schedules') }}">📅 Horarios</a>
        <a href="{{ route('schedule.assignment') }}">📅 Asignación de horarios</a>
    </div>
</div>

<x-nav-link route="users" label="Usuarios" />
