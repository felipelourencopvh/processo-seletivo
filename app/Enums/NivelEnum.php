<?php

declare(strict_types=1);

namespace App\Enums;

enum NivelEnum: string
{
    case fundamental = 'fundamental';
    case medio = 'medio';
    case superior = 'superior';

    public function getLabel(): string
    {
        return match ($this) {
            self::fundamental => 'Ensino Fundamental',
            self::medio => 'Ensino Médio',
            self::superior => 'Ensino Superior',
        };
    }
}
