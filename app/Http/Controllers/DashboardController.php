<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {

        // Top 5 de reservas
        $topReservations = $this->getTopReservations();

        // Cantidad canchas activas
        $amountFields = Field::where('status', 1)->count();

        // Cantidad de usuarios registrados
        $amountUsers = $this->countUserRegister();

        // Total de reservas
        $infoReservations = $this->countReservations();


        return view(
            'pages.admin.dashboard',
            compact('topReservations', 'amountFields', 'amountUsers', 'infoReservations')
        );
    }

    public function getTopReservations()
    {
        // Obtener información de las reservas
        $reservations = Reservation::select(
            'id',
            'field_id',
            'schedule_id',
            'user_id',
            'date',
            'status_reservation'
        )
            ->with([
                'field:id,name',
                'schedule:id,start_time,end_time',
                'user:id,name'
            ])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return $reservations;
    }

    public function countUserRegister()
    {
        // Obtener id del Rol User
        $userRole = Role::where('name', User::ROLE_USER)->first();

        $countUsers = User::where('role_id', $userRole->id)
            ->count();

        return $countUsers;
    }

    public function countReservations()
    {
        // Contar el total de reservas 
        $countTotal = Reservation::count();

        // Total de reservas activas
        // $countActives = Reservation::where()

        return [
            'amount' => $countTotal
        ];
    }
}
