<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

Volt::route('/', 'index')->name('home');
Volt::route('/processos-seletivos/{processo:slug}', 'processos-seletivos.show')->name('processos-seletivos.show');
