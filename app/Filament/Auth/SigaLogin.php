<?php

declare(strict_types=1);

namespace App\Filament\Auth;

use App\Services\Siga\SigaOidc;
use Filament\Facades\Filament;
use Filament\Pages\Auth\Login;

/**
 * Página de login dos painéis Filament (/admin e /app): quando o SIGA está configurado, desvia
 * direto para a entrada única do ecossistema. Sem SIGA (dev local), cai no formulário padrão.
 */
class SigaLogin extends Login
{
    public function mount(): void
    {
        if (app(SigaOidc::class)->configurado()) {
            $this->redirect(route('siga.entrar', ['painel' => Filament::getCurrentPanel()->getId()]));

            return;
        }

        parent::mount();
    }
}
