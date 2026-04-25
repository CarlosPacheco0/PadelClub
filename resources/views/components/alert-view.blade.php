    {{-- Alertas correspondientes desde el backend --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('success', 'Completado', @json(session('success')));
            });
        </script>
    @endif

    @if (session('login'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('success', 'Bienvenido', @json(session('login')));
            });
        </script>
    @endif


    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('error', 'Error', @json(session('error')));
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('info', 'Atención', @json(session('info')));
            });
        </script>
    @endif
