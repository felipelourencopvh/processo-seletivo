<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Support\Carbon;

readonly class SituacaoFilter
{
    public function __construct(public readonly Carbon $startDate, public readonly Carbon $endDate)
    {
    }

    public static function today(): self
    {
        return new self(startDate: now()->startOfDay(), endDate: now()->endOfDay());
    }

    public static function thisWheek(): self
    {
        return new self(startDate: now()->startOfWeek(), endDate: now()->endOfWeek());
    }

    public static function thisMonth(): self
    {
        return new self(startDate: now()->startOfMonth(), endDate: now()->endOfMonth());
    }
}
