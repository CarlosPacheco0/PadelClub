<?php

use Illuminate\Support\Facades\Route;

// ===============================
// Controladores
// ===============================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubAdminController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\RateManagementController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\SuperAdminController;
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

Route::middleware(['auth', 'validate_role:superadmin'])->group(function () {

    // Dashboard
    Route::get('/superadmin/dashboard', SuperAdminController::class)
        ->name('dashboard');

});


// ===============================
// ADMINISTRADOR (ROL ADMIN)
// ===============================

Route::middleware(['auth', 'validate_role:admin_club'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', ClubAdminController::class)
        ->name('dashboard_admin');

});


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

    Route::get('/fieldsFree/date', [ReservationsController::class, 'fieldsFree'])
        ->name('fieldsFree');

    Route::get('/user', fn() => view('user.dashboard'))
        ->name('user.dashboard');
});



// require __DIR__ . '/settings.php';
