{{-- @extends('layouts.layout')

@push('styles')
    @vite(['resources/css/martketplace/martketplace.css'])
@endpush

@section('content') --}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Maestro - SportBook</title>
    {{-- <title>Mi Club - SportBook</title> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Codigo de verificación CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- @vite(['resources/css/layout.css', 'resources/css/navbar.css', 'resources/css/alerts.css', 'resources/js/app.js']) --}}
    @vite(['resources/css/layout.css', 'resources/css/alerts.css', 'resources/css/martketplace/martketplace.css', 'resources/js/app.js'])

    @stack('styles')

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    {{-- Contenido dinámico --}}
    <main class="main-content">

        <div id="toast-container" class="toast-container"></div>

        <nav class="navbar">
            <div style="font-size: 1.5rem; font-weight: 800; color: #fff;"><i class="fas fa-futbol text-accent"></i>
                Sport<span class="text-accent">Book</span></div>
            <div class="nav-links">

                <x-alert-view></x-alert-view>
                
                {{-- ================= GUEST ================= --}}
                @guest
                    <x-nav-link route="login" label="Login" icon=""/>
                    <x-nav-link route="register" label="Registro" icon=""/>
                @endguest
                
            </div>
        </nav>

        <header class="search-hero">
            <h1 style="color: white; font-size: 3rem; font-weight: 800; margin-bottom: 1rem;">¿Qué vas a jugar hoy?</h1>
            <p style="color: var(--text-indigo); font-size: 1.125rem;">Encuentra y reserva al instante los mejores
                escenarios en Ocaña.</p>

            <div class="search-bar">
                <i class="fas fa-search" style="color: var(--text-muted); margin-left: 1rem;"></i>
                <input type="text" class="search-input" placeholder="Ej. Pádel, Fútbol 5...">
                <div class="search-divider"></div>
                <i class="fas fa-map-marker-alt" style="color: var(--text-muted); margin-left: 0.5rem;"></i>
                <select class="search-input" style="cursor: pointer;">
                    <option value="ocaña">Ocaña</option>
                    <option value="abrego">Ábrego</option>
                </select>
                <button class="btn-primary" style="margin-right: 0.25rem; padding: 0.75rem 2rem;">Buscar</button>
            </div>
        </header>

        <main class="club-grid">
            <div class="glass-panel" style="padding: 0; overflow: hidden;">
                <img src="https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=600&auto=format&fit=crop"
                    class="club-card-img" alt="Pádel Norte">
                <div class="club-card-body">
                    <div
                        style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <h3 style="color: white; font-size: 1.25rem; font-weight: 700;">Pádel Center Norte</h3>
                        <span style="color: #fbbf24; font-weight: bold; font-size: 0.875rem;"><i
                                class="fas fa-star"></i>
                            4.8</span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem;"><i
                            class="fas fa-map-marker-alt"></i> Sector El Bosque, Ocaña</p>
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
                        <span
                            style="background: rgba(79, 70, 229, 0.2); color: var(--brand-light); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem;">Pádel</span>
                        <span
                            style="background: rgba(16, 185, 129, 0.2); color: var(--accent-light); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem;">Techada</span>
                    </div>
                    <button class="btn-outline"
                        style="width: 100%; border-color: var(--brand); color: var(--brand-light);">Ver
                        Disponibilidad</button>
                </div>
            </div>
        </main>
    </main>

</body>

</html>
