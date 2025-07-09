<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware('auth');
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $this->authorize('viewDashboard', Auth::user());

        $pets = $this->dashboardService->getUserPets(
            Auth::id(),
            Auth::user()->email
        );

        $statistics = $this->dashboardService->getUserStatistics(Auth::id());

        return view('dashboard.cliente', [
            'pets' => $pets,
            'statistics' => $statistics
        ]);
    }
}
