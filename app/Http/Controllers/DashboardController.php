<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TeleconsultoriasResource;
use App\Models\Service;
use App\Models\Teleconsultoria;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    public function index(
        #[CurrentUser] User $currentUser
    ): Response {
        $teleconsultorias = Teleconsultoria::query()
            ->visibleTo($currentUser)
            ->paginate();

        return Inertia::render('Dashboard', [
            'teleconsultorias' => Inertia::scroll(TeleconsultoriasResource::collection($teleconsultorias)),
            'specialities'     => Service::whereHas('professional')->get(),
        ]);
    }
}
