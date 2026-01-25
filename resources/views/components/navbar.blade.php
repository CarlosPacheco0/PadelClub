<nav class="nav">
    @auth
        @if (auth()->user()->isUser())
            <a href="{{ route('home') }}" class="active">Inicio</a>
            {{-- <a href="{{ route('reservation') }}">Realizar reservas</a>
            <a href="{{ route('reservations.list') }}">Mis reservas</a> --}}

            <div class="reservation-menu">
                <button class="card-trigger" id="reservationTrigger">
                    <span class="caret">Reservas</span>
                </button>

                <div class="card-dropdown dropdown" id="reservationDropdown">
                    <a href="{{ route('reservation') }}">📅 Nueava reserva</a>
                    <a href="{{ route('reservations.list') }}">📅 Mis reservas</a>
                </div>
            </div>

            <a href="{{ route('information') }}">Información</a>
            <a href="{{ route('contact') }}">Contacto</a>
        @endif

        @if (auth()->user()->isAdmin())
            <a href="{{ route('dashboard') }}" class="active">Dashboard</a>
            <a href="{{ route('fields') }}">Canchas</a>
            <a href="{{ route('reservations') }}">Reservas</a>

            <div class="schedule-menu">
                <button class="card-trigger" id="scheduleTrigger">
                    <span class="caret">Horarios</span>
                </button>

                <div class="card-dropdown dropdown" id="scheduleDropdown">
                    <a href="{{ route('schedules') }}">📅 Horarios</a>
                    <a href="{{ route('schedule.assignment') }}">📅 Asignación de horarios</a>
                </div>
            </div>

            {{-- <a href="{{ route('schedules') }}">Horarios</a> --}}
            <a href="{{ route('users') }}">Usuarios</a>
        @endif

        <div class="user-menu" id="userMenu">
            <button class="card-trigger" id="userTrigger">
                <img src="https://icons.veryicon.com/png/o/miscellaneous/two-color-icon-library/user-286.png" class="avatar"
                    style="width: 40px">
                <span class="caret">▾</span>
            </button>

            <div class="card-dropdown dropdown" id="userDropdown">
                <a href="{{ route('profile.edit') }}">⚙️ Configuración</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">🚪 Cerrar sesión</button>
                </form>
            </div>
        </div>

    @endauth

    @guest
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Registro</a>
    @endguest

</nav>
