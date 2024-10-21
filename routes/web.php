<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

Volt::route('/', 'index')->name('home');
Volt::route('/login', 'login')->name('login');

Volt::route('/registrar', 'registrar')->name('registrar');
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});
Volt::route('/processos-seletivos/{processo:slug}', 'processos-seletivos.show')->name('processos-seletivos.show');

Route::middleware('auth')->group(function () {
    Volt::route('/candidato', 'candidato.index')->name('candidato.index');
    Volt::route('/candidato/inscricoes/{uuid}', 'inscricoes.show')->name('inscricoes.show');
    Volt::route('/candidato/editar', 'candidato.edit')->name('candidato.edit');
    Volt::route('/processos-seletivos/{processo:slug}/inscrever', 'processos-seletivos.inscrever')->name('processos-seletivos.inscrever');
});
