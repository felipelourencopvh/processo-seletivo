<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserMenu extends Component
{
    public ?User $user;

    public function __construct()
    {
        if (auth()->check()) {
            $this->user = auth()->user();
        } else {
            $this->user = null;
        }

    }

    public function render(): View
    {
        return view('components.user-menu');
    }
}
