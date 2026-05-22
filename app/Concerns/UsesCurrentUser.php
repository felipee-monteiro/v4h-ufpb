<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

trait UsesCurrentUser
{
    private $currentUser;

    public function __construct(
        #[CurrentUser()] User $user
    )
    {
        $this->currentUser = $user;
    }
}
