<?php

declare(strict_types=1);

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\View\Component;

class Back extends Component
{
    public function render(): ViewView|Closure|string
    {
        return <<<'HTML'
                    <x-button label="Voltar" {{ $attributes->whereDoesntStartWith('class') }} {{ $attributes->class(['hover:-rotate-3']) }}/>
            HTML;
    }
}
