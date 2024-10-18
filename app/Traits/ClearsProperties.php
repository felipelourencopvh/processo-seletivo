<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Reset all components properties
 */
trait ClearsProperties
{
    public function clear(): void
    {
        $this->reset();
    }
}
