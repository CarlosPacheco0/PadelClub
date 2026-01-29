<nav class="nav">

    @auth

        {{-- ================= USER ================= --}}
        @if (auth()->user()->isUser())

            <x-nav-user></x-nav-user>

        @endif


        {{-- ================= ADMIN ================= --}}
        @if (auth()->user()->isAdmin())

            <x-nav-admin></x-nav-admin>

        @endif


        {{-- ================= PROFILE ================= --}}
        <div class="nav-dropdown user-dropdown">
            <button class="nav-trigger">
                <img src="https://icons.veryicon.com/png/o/miscellaneous/two-color-icon-library/user-286.png"
                     class="avatar" width="36">
                <span class="caret">▾</span>
            </button>

            <div class="nav-menu">
                <a href="{{ route('profile.edit') }}">⚙️ Configuración</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        🚪 Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

    @endauth


    {{-- ================= GUEST ================= --}}
    @guest
        <x-nav-link route="login" label="Login" />
        <x-nav-link route="register" label="Registro" />
    @endguest

</nav>
