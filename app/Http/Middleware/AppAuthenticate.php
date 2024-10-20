<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Model;

class AppAuthenticate extends Authenticate
{
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        if ( ! $guard->check()) {
            $this->unauthenticated($request, $guards);

            return;
        }

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $guard->user();

        $panel = Filament::getCurrentPanel();

        if ($user->roles()->count() === 0) {
            redirect('/');
        }
    }

    protected function redirectTo($request): ?string
    {

        return Filament::getLoginUrl();
    }
}
