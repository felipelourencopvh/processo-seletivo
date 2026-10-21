<?php

declare(strict_types=1);

use App\Http\Controllers\Siga\ProvisionamentoController;
use App\Http\Middleware\AutenticarSiga;
use Illuminate\Support\Facades\Route;

// ADR-016 §5: o SIGA cria o usuário e define os perfis dele aqui.
Route::prefix('siga/api')->middleware(AutenticarSiga::class)->group(function (): void {
    Route::get('perfis', [ProvisionamentoController::class, 'perfis']);
    Route::get('alcances', [ProvisionamentoController::class, 'alcances']);
    Route::get('usuarios/{cpf}', [ProvisionamentoController::class, 'consultar'])->where('cpf', '\d{11}');
    Route::put('usuarios/{cpf}', [ProvisionamentoController::class, 'aplicar'])->where('cpf', '\d{11}');
});
