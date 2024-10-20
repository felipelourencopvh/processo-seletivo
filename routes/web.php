<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

Volt::route('/login', 'login')->name('login');
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});
Volt::route('/', 'index')->name('home');
Volt::route('/processos-seletivos/{processo:slug}', 'processos-seletivos.show')->name('processos-seletivos.show');
