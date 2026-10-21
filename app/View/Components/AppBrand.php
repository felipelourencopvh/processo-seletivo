<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /** Create a new component instance. */
    public function __construct()
    {

    }

    /** Get the view / contents that represent the component. */
    public function render(): View|Closure|string
    {
        return <<<'blade'
                <a href="/" wire:navigate class="inline-flex items-center">
                    <x-marca />
                </a>
            blade;
    }
}
