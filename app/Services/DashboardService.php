<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardService
{
    protected $cacheMinutes;

    public function __construct()
    {
        $this->cacheMinutes = config('cache.dashboard_ttl', 10);
    }

    public function getUserPets($userId, $userEmail)
    {
        $cacheKey = $this->generateCacheKey($userId, $userEmail);

        return Cache::remember(
            $cacheKey,
            now()->addMinutes($this->cacheMinutes),
            fn () => $this->fetchUserPets($userId, $userEmail)
        );
    }

    public function getUserStatistics($userId)
    {
        $cacheKey = "user_statistics:{$userId}";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes($this->cacheMinutes),
            fn () => $this->calculateUserStatistics($userId)
        );
    }

    protected function generateCacheKey($userId, $userEmail)
    {
        $lastUpdated = $this->getLastUpdateTimestamp($userId, $userEmail);
        return "user_pets:{$userId}:{$lastUpdated}";
    }

    protected function getLastUpdateTimestamp($userId, $userEmail)
    {
        $timestamp = Pet::where(function ($query) use ($userId, $userEmail) {
            $query->where('user_id', $userId)
                  ->orWhere('correo_owner', $userEmail);
        })->max('updated_at');

        return $timestamp ? Carbon::parse($timestamp)->timestamp : 'no_pets';
    }

    protected function fetchUserPets($userId, $userEmail)
    {
        return Pet::with(['payment', 'vaccinationRecords'])
            ->where(function ($query) use ($userId, $userEmail) {
                $query->where('user_id', $userId)
                      ->orWhere('correo_owner', $userEmail);
            })
            ->latest()
            ->get();
    }

    protected function calculateUserStatistics($userId)
    {
        return [
            'total_pets' => Pet::where('user_id', $userId)->count(),
            'pending_vaccinations' => $this->getPendingVaccinations($userId),
            'upcoming_appointments' => $this->getUpcomingAppointments($userId),
            'recent_activities' => $this->getRecentActivities($userId),
        ];
    }

    protected function getPendingVaccinations($userId)
    {
        return VaccinationRecord::whereHas('pet', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('next_vaccination_date', '<=', now()->addMonths(1))
        ->count();
    }

    protected function getUpcomingAppointments($userId)
    {
        // Implementar lógica para citas próximas
        return [];
    }

    protected function getRecentActivities($userId)
    {
        // Implementar lógica para actividades recientes
        return [];
    }
}
