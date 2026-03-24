<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Club - SportBook</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/layout.css', 'resources/css/dashboard/dashboard.css'])

</head>
<body>
    <aside class="sidebar">
        <div class="brand-logo"><i class="fas fa-futbol text-accent" style="margin-right: 8px;"></i>Sport<span class="text-accent">Admin</span></div>
        <ul class="nav-menu">
            <li><a href="#" class="nav-item active"><i class="fas fa-calendar-check"></i> Reservas de Hoy</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-chart-bar"></i> Mis Ingresos</a></li>
            <li><a href="#" class="nav-item"><i class="fas fa-cog"></i> Configurar Sede</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div style="color: var(--text-muted);"><i class="far fa-calendar-alt"></i> Hoy: 24 de Octubre</div>
            <div style="color: white; font-weight: bold;">Pádel Center Norte</div>
        </header>

        <div class="content-area">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="color: white; font-size: 1.5rem;">Próximos Partidos</h2>
                <button class="btn-primary"><i class="fas fa-plus"></i> Nueva Reserva Manual</button>
            </div>

            <div class="glass-panel">
                <table class="table-container">
                    <thead><tr><th>Hora</th><th>Cancha</th><th>Deportista</th><th>Estado</th></tr></thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: bold;">18:00 - 19:30</td>
                            <td style="color: var(--brand-light);">Cancha 1 (Panorámica)</td>
                            <td>Andrés Deportista <br><small style="color: var(--text-muted);">320 987 6543</small></td>
                            <td><span class="badge" style="background: rgba(16,185,129,0.15); color: var(--accent-light); border: 1px solid var(--accent);">Pagado</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>