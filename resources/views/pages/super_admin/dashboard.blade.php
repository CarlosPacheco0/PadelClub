<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Maestro - SportBook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/layout.css', 'resources/css/dashboard/dashboard.css'])

</head>
<body>
    <aside class="sidebar">
        <div class="brand-logo"><i class="fas fa-crown" style="color: #fbbf24; margin-right: 8px;"></i>Sport<span style="color: #fbbf24;">Master</span></div>
        <ul class="nav-menu">
            <li><a href="#" class="nav-item active"><i class="fas fa-chart-line"></i> Visión Global</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-building"></i> Todos los Clubes</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-users"></i> Deportistas</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div style="color: var(--text-muted);">Superadmin Dashboard</div>
            <div style="color: white; font-weight: bold;"><i class="fas fa-user-shield text-accent" style="margin-right: 8px;"></i> Tú (Admin)</div>
            <div class="nav-menu">
                {{-- <a href="">⚙️ Configuración</a> --}}

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        🚪 Cerrar sesión
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            <h2 style="color: white; margin-bottom: 1.5rem; font-size: 1.5rem;">Métricas de la Plataforma</h2>
            
            <div class="kpi-grid">
                <div class="glass-panel kpi-card">
                    <div class="kpi-icon" style="background: rgba(79, 70, 229, 0.2); color: var(--brand-light);"><i class="fas fa-building"></i></div>
                    <div><div class="kpi-value">12</div><div class="kpi-label">Clubes Registrados</div></div>
                </div>
                <div class="glass-panel kpi-card">
                    <div class="kpi-icon" style="background: rgba(16, 185, 129, 0.2); color: var(--accent-light);"><i class="fas fa-users"></i></div>
                    <div><div class="kpi-value">1,240</div><div class="kpi-label">Usuarios Activos</div></div>
                </div>
                <div class="glass-panel kpi-card">
                    <div class="kpi-icon" style="background: rgba(245, 158, 11, 0.2); color: #fcd34d;"><i class="fas fa-money-bill-wave"></i></div>
                    <div><div class="kpi-value">$4.5M</div><div class="kpi-label">Volumen (Mes actual)</div></div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>