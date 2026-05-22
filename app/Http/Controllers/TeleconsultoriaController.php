<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Concerns\UsesCurrentUser;
use App\Enums\RoleName;
use App\Http\Requests\IndexTeleconsultoriaRequest;
use App\Http\Requests\StoreTeleconsultoriaOpinionRequest;
use App\Http\Requests\StoreTeleconsultoriaRequest;
use App\Models\Teleconsultoria;
use Illuminate\Http\RedirectResponse;
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
        $user           = $this->currentUser;

        $query = Teleconsultoria::whereBelongsTo($user, 'solicitante');

        $search = $request->input('search');

        if ($search) {
            $query->where(static function ($q) use ($search): void {
                $q->whereHas('service', static function ($s) use ($search): void {
                    $s->where('title', 'ilike', "%{$search}%");
                })
                    ->orWhere('uuid', 'ilike', "%{$search}%");
            });
        }

        if ($statuses = $request->input('status', [])) {
            $query->whereIn('status', (array) $statuses);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $teleconsultorias = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $teleconsultorias->getCollection()->transform(static function (Teleconsultoria $teleconsultoria) use ($user) {
            return [
                'id'       => $teleconsultoria->uuid,
                'short_id' => \mb_substr($teleconsultoria->uuid, 0, 8),
                'patient'  => $user->name,
                'service'  => $teleconsultoria->service?->title,
                'date'     => $teleconsultoria->created_at->format('Y-m-d'),
                'status'   => $teleconsultoria->status,
            ];
        });

        return Inertia::render('Solicitante/Teleconsultorias/Index', [
            'filters' => [
                'search'    => $search ?: '',
                'status'    => $statuses ?: [],
                'date_from' => $request->input('date_from') ?: null,
                'date_to'   => $request->input('date_to') ?: null,
            ],
            'teleconsultorias' => $teleconsultorias,
            'canCreate'        => $user->hasRole(RoleName::SOLICITANTE->value),
        ]);
    }

    public function show(Teleconsultoria $teleconsultoria): Response
    {
        $teleconsultoria->load('service.professional', 'solicitante');

        return Inertia::render('Solicitante/Show', [
            'teleconsultoria' => [
                'id'           => $teleconsultoria->uuid,
                'patient'      => $teleconsultoria->solicitante?->name,
                'service'      => $teleconsultoria->service?->title,
                'professional' => $teleconsultoria->service?->professional?->name,
                'status'       => $teleconsultoria->status,
                'created_at'   => $teleconsultoria->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function registerOpinion(Teleconsultoria $teleconsultoria, StoreTeleconsultoriaOpinionRequest $request): RedirectResponse
    {
        $teleconsultoria->load('service.professional');

        if ($teleconsultoria->service?->professional?->uuid !== $this->currentUser->uuid) {
            abort(403);
        }

        $teleconsultoria->professional_opinion = $request->input('professional_opinion');
        $teleconsultoria->save();

        return back();
    }

    public function store(StoreTeleconsultoriaRequest $request): RedirectResponse
    {
        Teleconsultoria::create([
            ...$request->validated(),
            'solicitante_uuid' => $request->user()->uuid,
        ]);

        return to_route('dashboard.index');
    }
}
