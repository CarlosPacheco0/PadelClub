<nav class="nav">
    <ul class="nav-menu">

        @auth
            {{-- ================= USER ================= --}}
            @if (auth()->check() && auth()->user()->role === 'usuario')
                <x-nav-user></x-nav-user>
            @endif

            {{-- ================= SUPERADMIN ================= --}}
            @if (auth()->check() && auth()->user()->role === 'admin_club')
                <x-nav-club></x-nav-club>
            @endif

            {{-- ================= ADMIN CLUB ================= --}}
            @if (auth()->check() && auth()->user()->role === 'superadmin')
                <x-nav-admin></x-nav-admin>
            @endif

            {{-- ================= PROFILE ================= --}}
            <form method="POST" action="{{ route('logout') }}" style="width: 100%; margin: 0;">
                @csrf

                <button type="submit" class="nav-item" id="sesion-close">
                    Cerrar sesión
                </button>
            </form>

            {{-- </div> --}}

        @endauth

    </ul>
</nav>
