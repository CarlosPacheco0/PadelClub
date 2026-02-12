{{-- <x-nav-link route="home" label="Inicio" /> --}}

@php
    $reservasActive = request()->routeIs('reservation', 'reservations.*');
@endphp

<div class="nav-dropdown">
    <button class="nav-trigger {{ $reservasActive ? 'active' : '' }}">
        Reservas <span class="caret">▾</span>
    </button>

    <div class="nav-menu">
        <a href="{{ route('reservation') }}">📅 Nueva reserva</a>
        <a href="{{ route('reservations.list') }}">📅 Mis reservas</a>
    </div>
</div>

<x-nav-link route="information" label="Información" />
<x-nav-link route="contact" label="Contacto" />
