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
                <a href="/" wire:navigate>
                    <!-- Hidden when collapsed -->
                    <div class="flex flex-row">
                        <div class="flex flex-col items-center gap-2">
                            <span class="font-bold text-3xl me-3 bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-white ">
                                Processos Seletivos
                            </span>
                            <span class="font-bold text-xl me-3 bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-white ">
                                Prefeitura Municipal de Porto Velho
                            </span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                        <img src="{{ asset('media/brasao.svg') }}" alt="logo" class="w-36 md:w-[250px]" />
                        </div>
                    </div>
                </a>
            blade;
    }
}
