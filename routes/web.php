<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

Volt::route('/', 'index')->name('home');

Volt::route('/login', 'auth.login')->name('login');
Volt::route('/registrar', 'auth.registrar')->name('registrar');
Volt::route('/recuperar-senha', 'auth.recuperar-senha')->name('recuperar-senha');
Volt::route('/alterar-senha/{token}', 'auth.alterar-senha')->middleware('guest')->name('password.reset');
Volt::route('/trocar-senha', 'auth.recuperar-senha')->name('trocar-senha');
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
