<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Concerns\UsesCurrentUser;
use App\Models\Service;
use App\Models\Teleconsultoria;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Response;

final class DashboardController extends Controller
{
    use UsesCurrentUser;

    public function index(): Response
    {
        $teleconsultorias = Teleconsultoria::with('service.professional')
            ->where(function (Builder $query): void {
                $query->whereBelongsTo($this->currentUser, 'solicitante')
                    ->orWhereHas('service.professional', function (Builder $query): void {
                        $query->whereKey($this->currentUser->getKey());
                    });
            })
            ->latest()
            ->get()
            ->map(static function (Teleconsultoria $teleconsultoria): array {
                return [
                    'id'                    => $teleconsultoria->getKey(),
                    'patient'               => $teleconsultoria->patient_name,
                    'initials'              => $teleconsultoria->patient_initials,
                    'specialty'             => $teleconsultoria->service?->title ?? '',
                    'date'                  => $teleconsultoria->created_at->toDateString(),
                    'status'                => $teleconsultoria->status,
                    'diagnostic_hypothesis' => $teleconsultoria->diagnostic_hypothesis,
                    'clinical_history'      => $teleconsultoria->clinical_history,
                    'professional'          => $teleconsultoria->service->professional->name,
                    'professional_uuid'     => $teleconsultoria->service->professional->getKey(),
                    'professional_opinion'  => $teleconsultoria->professional_opinion,
                ];
            });

        return inertia('Dashboard', [
            'teleconsultorias' => $teleconsultorias,
            'specialities'     => Service::whereHas('professional')->get(),
        ]);
    }
}
