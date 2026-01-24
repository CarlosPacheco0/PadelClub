@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/contact.css'])
@endpush

@section('content')
    <section class="section">
        <h2>Contacto</h2>
        <p class="text-muted">
            ¿Tienes dudas o necesitas ayuda? Contáctanos
        </p>

        <div class="contacto-grid">

            <!-- INFO -->
            <div class="contacto-info">
                <h3>📞 Información de contacto</h3>

                <p><strong>WhatsApp:</strong><br> +57 300 000 0000</p>
                <p><strong>Teléfono:</strong><br> (01) 234 5678</p>
                <p><strong>Email:</strong><br> contacto@padelclub.com</p>

                <p class="nota">
                    Horario de atención:<br>
                    Lunes a Viernes 08:00 - 22:00
                </p>
            </div>

            <!-- FORMULARIO -->
            <div class="contacto-form">
                <h3>✉️ Escríbenos</h3>

                <form>
                    <div class="campo">
                        <label>Nombre</label>
                        <input type="text" placeholder="Tu nombre">
                    </div>

                    <div class="campo">
                        <label>Correo</label>
                        <input type="email" placeholder="correo@email.com">
                    </div>

                    <div class="campo">
                        <label>Mensaje</label>
                        <textarea rows="4" placeholder="Escribe tu mensaje"></textarea>
                    </div>

                    <button class="btn-confirmar">Enviar mensaje</button>
                </form>
            </div>

        </div>
    </section>
@endsection
