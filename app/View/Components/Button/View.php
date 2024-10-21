<?php

declare(strict_types=1);

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\View\Component;

class View extends Component
{
    public function render(): ViewView|Closure|string
    {
        return <<<'HTML'
                    <x-button icon="o-magnifying-glass" tooltip="Visualizar" {{ $attributes->whereDoesntStartWith('class') }} spinner {{ $attributes->class(['btn btn-sm btn-circle bg-blue-100 hover:bg-blue-300 text-blue-600 hover:text-blue-100']) }}/>
            HTML;
    }
}
