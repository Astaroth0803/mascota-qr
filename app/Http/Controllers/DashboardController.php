<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Usar caché para mejorar el rendimiento de las consultas
        $totalUsers = Cache::remember('totalUsers', 60, function () {
            return User::count();
        });

        $totalPets = Cache::remember('totalPets', 60, function () {
            return Pet::count();
        });

        $pendingRequests = Cache::remember('pendingRequests', 60, function () {
            return Solicitud::count();
        });

        return view('dashboard/administrador', compact('totalUsers', 'totalPets', 'pendingRequests'));
    }
}
