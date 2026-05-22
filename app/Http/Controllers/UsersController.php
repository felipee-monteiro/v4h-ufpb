<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

final class UsersController extends Controller
{
    /**
     * Display a paginated list of registered users.
     */
    public function index(IndexUserRequest $request): Response
    {
        $users = User::query()
            ->select([
                'uuid',
                'name',
                'email',
                'email_verified_at',
                'created_at'
            ])
            ->search($search = $request->search())
            ->newest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'filters' => [
                'search' => $search,
            ],
            'users' => UserResource::collection($users),
        ]);
    }
}
