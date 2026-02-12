@extends('layouts.layout')

@push('styles')
    @vite(['resources/css/contact.css'])
@endpush

@section('content')
    <section class="page-container">
        
        <div class="header-section text-center">
            <h1 class="content-title">Contáctanos</h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                ¿Tienes alguna duda sobre tus reservas o el club? Estamos aquí para ayudarte a mejorar tu juego.
            </p>
        </div>

        <div class="contact-grid">

            <div class="contact-card info-panel">
                <div class="card-header">
                    <div class="icon-box icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </div>
                    <h3>Información de Contacto</h3>
                </div>

                <div class="info-list">
                    <div class="info-item">
                        <span class="label">WhatsApp</span>
                        <a href="#" class="value highlight">+57 300 000 0000</a>
                    </div>
                    
                    <div class="info-item">
                        <span class="label">Teléfono Fijo</span>
                        <span class="value">(01) 234 5678</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Email Soporte</span>
                        <a href="mailto:contacto@padelclub.com" class="value">contacto@padelclub.com</a>
                    </div>
                </div>

                <div class="schedule-box">
                    <h4>🕒 Horario de Atención</h4>
                    <p>Lunes a Viernes: <span class="text-white">08:00 - 22:00</span></p>
                    <p>Fines de Semana: <span class="text-white">08:00 - 20:00</span></p>
                </div>
            </div>

            <div class="contact-card form-panel">
                <div class="card-header">
                    <div class="icon-box icon-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <h3>Envíanos un mensaje</h3>
                </div>

                <form>
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" class="input-neon" placeholder="Ej. Juan Pérez">
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" class="input-neon" placeholder="juan@correo.com">
                    </div>

                    <div class="form-group">
                        <label>Tu Mensaje</label>
                        <textarea rows="4" class="input-neon" placeholder="¿En qué podemos ayudarte hoy?"></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        Enviar Mensaje
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>

        </div>
    </section>
@endsection