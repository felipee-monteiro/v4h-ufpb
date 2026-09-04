<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Teleconsultoria;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Response;

final class DashboardController extends Controller
{
    public function index(
        #[CurrentUser] User $currentUser
    ): Response {
        $teleconsultorias = Teleconsultoria::query()->getDashboardIndexDataForUser($currentUser)
            ->map(fn (Teleconsultoria $teleconsultoria): array => [
                'id'                    => $teleconsultoria->getKey(),
                'patient'               => $teleconsultoria->patient_name,
                'patient_initials'      => $teleconsultoria->patient_initials,
                'specialty'             => $teleconsultoria->service?->title ?? '',
                'date'                  => $teleconsultoria->created_at->toDateString(),
                'status'                => $teleconsultoria->status,
                'diagnostic_hypothesis' => $teleconsultoria->diagnostic_hypothesis,
                'clinical_history'      => $teleconsultoria->clinical_history,
                'professional'          => $teleconsultoria->service->professional->name,
                'professional_uuid'     => $teleconsultoria->service->professional->getKey(),
                'professional_opinion'  => $teleconsultoria->professional_opinion,
            ]);

        return inertia('Dashboard', [
            'teleconsultorias' => $teleconsultorias,
            'specialities'     => Service::whereHas('professional')->get(),
        ]);
    }
}
