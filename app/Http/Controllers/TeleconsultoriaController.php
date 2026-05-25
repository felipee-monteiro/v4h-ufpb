<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Teleconsultoria\RegisterOpinion;
use App\Concerns\UsesCurrentUser;
use App\Http\Requests\IndexTeleconsultoriaRequest;
use App\Http\Requests\StoreTeleconsultoriaOpinionRequest;
use App\Http\Requests\StoreTeleconsultoriaRequest;
use App\Models\Teleconsultoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class TeleconsultoriaController extends Controller
{
    use UsesCurrentUser;

    /**
     * Show teleconsultorias with filters and list.
     */
    public function index(IndexTeleconsultoriaRequest $request): Response
    {
        $user = $this->currentUser;

        $query = Teleconsultoria::whereBelongsTo($user, 'solicitante');

        $teleconsultorias = $query->orderByDesc(Teleconsultoria::CREATED_AT)->paginate(10)->withQueryString();

        $teleconsultorias->getCollection()->transform(static function (Teleconsultoria $teleconsultoria) use ($user) {
            return [
                'id'      => $teleconsultoria->uuid,
                'patient' => $user->name,
                'service' => $teleconsultoria->service?->title,
                'date'    => $teleconsultoria->created_at->format('Y-m-d'),
                'status'  => $teleconsultoria->status,
            ];
        });

        return Inertia::render('Solicitante/Teleconsultorias/Index', [
            'filters' => [
                'date_from' => $request->input('date_from') ?: null,
                'date_to'   => $request->input('date_to') ?: null,
            ],
            'teleconsultorias' => $teleconsultorias,
        ]);
    }

    public function show(Teleconsultoria $teleconsultoria): Response
    {
        $teleconsultoria->load('service.professional', 'solicitante');

        return Inertia::render('Solicitante/Show', [
            'teleconsultoria' => [
                'id'           => $teleconsultoria->uuid,
                'patient'      => $teleconsultoria->solicitante->name,
                'service'      => $teleconsultoria->service->title,
                'professional' => $teleconsultoria->service->professional->name,
                'status'       => $teleconsultoria->status,
                'created_at'   => $teleconsultoria->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function registerOpinion(
        StoreTeleconsultoriaOpinionRequest $request,
        Teleconsultoria $teleconsultoria,
        RegisterOpinion $action,
    ): RedirectResponse {
        $action($teleconsultoria, $request->professionalOpinion());

        return back();
    }

    public function store(StoreTeleconsultoriaRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            Teleconsultoria::create([
                ...$request->validated(),
                'solicitante_uuid' => $this->currentUser->getKey(),
            ]);
        });

        return to_route('dashboard.index');
    }
}
