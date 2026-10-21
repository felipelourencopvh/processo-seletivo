<?php

declare(strict_types=1);

namespace App\Services\Siga;

use RuntimeException;

/** Falha no login pelo SIGA, com a mensagem pronta para a pessoa. */
class SigaException extends RuntimeException
{
}
