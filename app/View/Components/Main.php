<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Main extends \Mary\View\Components\Main
{
    public function render(): View|Closure|string
    {
        return view('components.main');
    }
}
