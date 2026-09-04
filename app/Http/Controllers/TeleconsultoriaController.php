<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Teleconsultoria\RegisterOpinion;
use App\Http\Requests\IndexTeleconsultoriaRequest;
use App\Http\Requests\StoreTeleconsultoriaOpinionRequest;
use App\Http\Requests\StoreTeleconsultoriaRequest;
use App\Models\Teleconsultoria;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

final class TeleconsultoriaController extends Controller
{
    public function index(
        IndexTeleconsultoriaRequest $request,
        #[CurrentUser] User $user
    ): Response {
        $teleconsultorias = Teleconsultoria::query()->getTeleconsultoriasBySolicitante($user)->paginate(10)->withQueryString();

        $teleconsultorias->getCollection()->transform(fn (Teleconsultoria $teleconsultoria): array => [
            'id'      => $teleconsultoria->getKey(),
            'patient' => $user->name,
            'service' => $teleconsultoria->service?->title,
            'date'    => $teleconsultoria->created_at->format(),
            'status'  => $teleconsultoria->status,
        ]);

        return inertia('Solicitante/Teleconsultorias/Index', [
            'filters' => [
                'date_from' => $request->from(),
                'date_to'   => $request->to(),
            ],
            'teleconsultorias' => $teleconsultorias,
        ]);
    }

    public function show(Teleconsultoria $teleconsultoria): Response
    {
        $teleconsultoria->load('service.professional', 'solicitante');

        return inertia('Solicitante/Show', [
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
        $action->execute($teleconsultoria, $request->professionalOpinion());

        return back();
    }

    public function store(
        StoreTeleconsultoriaRequest $request,
        #[CurrentUser] User $user
    ): RedirectResponse {
        DB::transaction(fn () => Teleconsultoria::create([
            ...$request->validated(),
            'solicitante_uuid' => $user->getKey(),
        ]));

        return to_route('dashboard.index');
    }
}
