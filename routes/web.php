<?php

use Illuminate\Support\Facades\Route;

// ===============================
// Controladores
// ===============================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\UsersController;

// ===============================
// RUTAS PÚBLICAS
// ===============================

Route::get('/', HomeController::class)->name('home');

Route::get('/information', InformationController::class)->name('information');
Route::get('/contact', ContactController::class)->name('contact');


// ===============================
// AUTENTICACIÓN
// ===============================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', RegisterController::class)->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ===============================
// USUARIOS AUTENTICADOS (ROL USER)
// ===============================

Route::middleware(['auth', 'validate_role:user'])->group(function () {

    // Reservas (Usuario)
    Route::get('/reservation', [ReservationsController::class, 'index'])
        ->name('reservation');

    Route::post('/reservation', [ReservationsController::class, 'generateReservation'])
        ->name('reservation.generate');

    Route::post('/reservation-save', [ReservationsController::class, 'save'])
        ->name('reservation.save');

    Route::get('/reservations-list', [ReservationsController::class, 'reservationsList'])
        ->name('reservations.list');

    // Edición de reserva desde el usuairo
    Route::put('/reservations-list', [ReservationsController::class, 'updateReservation'])
        ->name('reservation-list');

    Route::put('/reservation-cancel', [ReservationsController::class, 'cancel'])
        ->name('reservation.cancel');



    Route::post('/schedulesFree', [ReservationsController::class, 'schedulesFree'])
        ->name('schedulesFree');

    Route::get('/fieldsFree', [ReservationsController::class, 'fieldsFree'])
        ->name('fieldsFree');

    Route::get('/user', fn() => view('user.dashboard'))
        ->name('user.dashboard');
});

// ===============================
// ADMINISTRADOR (ROL ADMIN)
// ===============================

Route::middleware(['auth', 'validate_role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');



    // Gestión de Canchas
    Route::get('/fields', FieldsController::class)
        ->name('fields');

    Route::post('/field', [FieldsController::class, 'save'])
        ->name('field.save');

    Route::put('/field', [FieldsController::class, 'update'])
        ->name('field.update');

    Route::delete('/field', [FieldsController::class, 'delete'])
        ->name('field.delete');



    // Gestión de Reservas
    Route::get('/reservations', [ReservationsController::class, 'managementReservation'])
        ->name('reservations');

    Route::put('/reservations', [ReservationsController::class, 'update'])
        ->name('reservation.update');

    Route::delete('/reservations', [ReservationsController::class, 'delete'])
        ->name('reservation.delete');

    Route::get('/fields-free', [ReservationsController::class, 'fieldsFree'])
        ->name('fields.free');

    Route::put('/reservations-cancel', [ReservationsController::class, 'cancel'])
        ->name('res.cancel');




    // Gestion de horarios
    Route::get('/schedules', SchedulesController::class)->name('schedules');

    Route::post('/schedule', [SchedulesController::class, 'create'])->name('schedule.create');

    Route::put('/schedule', [SchedulesController::class, 'update'])->name('schedule.update');

    Route::delete('schedule', [SchedulesController::class, 'delete'])->name('schedule.delete');

    // Asignación de horarios
    Route::get('/schedules-assignment', [SchedulesController::class, 'assignment'])
        ->name('schedule.assignment');

    // Guardar horarios seleccionados por fecha
    Route::post('/schedules-assignment', [SchedulesController::class, 'store'])
        ->name('assignment.store');

    // Obtener info de la fecha seleccionada
    Route::get('/getInfo-date', [SchedulesController::class, 'getInfoDate'])
        ->name('assignment.info');

    // Eliminar horarios asignados
    Route::delete('/getInfo-date', [SchedulesController::class, 'assignmentDelete'])
        ->name('assignment.delete');




    // Gestión de Usuarios
    Route::get('/users', UsersController::class)
        ->name('users');

    // Crear registro ( Usuario )
    Route::post('/user', [UsersController::class, 'store'])->name('user.store');

    // Edición de registro ( Usuario )
    Route::put('/user', [UsersController::class, 'update'])->name('user.update');

    // Eliminar registro ( Usuario )
    Route::delete('/user', [UsersController::class, 'delete'])->name('user.delete');
});


// require __DIR__ . '/settings.php';
