<?php

declare(strict_types=1);

namespace App\View\Components\Layouts\Parts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    public function render(): View
    {
        return view('components.layouts.parts.footer');
    }
}
