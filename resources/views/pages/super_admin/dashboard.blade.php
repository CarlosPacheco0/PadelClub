@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/dashboard/dashboard.css'])
@endpush

@section('content')
    <header class="topbar">
        <div style="color: var(--text-muted);">Superadmin Dashboard</div>
        <div style="color: white; font-weight: bold;"><i class="fas fa-user-shield text-accent" style="margin-right: 8px;"></i>
            Tú (Admin)</div>
    </header>

    <div class="content-area">
        <h2 style="color: white; margin-bottom: 1.5rem; font-size: 1.5rem;">Métricas de la Plataforma</h2>

        <div class="kpi-grid">
            <div class="glass-panel kpi-card">
                <div class="kpi-icon" style="background: rgba(79, 70, 229, 0.2); color: var(--brand-light);"><i
                        class="fas fa-building"></i></div>
                <div>
                    <div class="kpi-value">12</div>
                    <div class="kpi-label">Clubes Registrados</div>
                </div>
            </div>
            <div class="glass-panel kpi-card">
                <div class="kpi-icon" style="background: rgba(16, 185, 129, 0.2); color: var(--accent-light);"><i
                        class="fas fa-users"></i></div>
                <div>
                    <div class="kpi-value">1,240</div>
                    <div class="kpi-label">Usuarios Activos</div>
                </div>
            </div>
            <div class="glass-panel kpi-card">
                <div class="kpi-icon" style="background: rgba(245, 158, 11, 0.2); color: #fcd34d;"><i
                        class="fas fa-money-bill-wave"></i></div>
                <div>
                    <div class="kpi-value">$4.5M</div>
                    <div class="kpi-label">Volumen (Mes actual)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
