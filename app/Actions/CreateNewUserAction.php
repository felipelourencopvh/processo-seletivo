<?php

declare(strict_types=1);

namespace App\Actions;

use App\Livewire\Forms\NewUserForm;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewUserAction
{
    use AsAction;

    public function handle(NewUserForm $data): User
    {
        $user = new User();
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = bcrypt($data->password);
        $user->email_verified_at = now();
        $user->save();

        $user->info()->create($data->toArray());

        return $user;
    }
}
