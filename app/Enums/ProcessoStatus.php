<?php

declare(strict_types=1);

namespace App\Enums;

enum ProcessoStatus: string
{
    case ABERTO = 'Aberto';
    case EM_BREVE = 'Em breve';
    case EM_ANDAMENTO = 'Em andamento';
    case ENCERRADO = 'Encerrado';

    public static function getArray()
    {
        $array = [];
        $array[] = ['id' => 'Todos', 'name' => 'Todos'];
        foreach (self::cases() as $case) {
            $array[] = ['id' => $case->name, 'name' => $case->value];
        }

        return $array;
    }

    public static function getLabel(?string $status): string
    {
        if (is_null($status)) {
            return  'Todos';
        }

        return match (self::from($status)) {
            self::ABERTO, self::ENCERRADO, self::EM_ANDAMENTO, self::EM_BREVE => $status->value,
        };
    }

    public function isAberto(): bool
    {
        return self::ABERTO === $this;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ABERTO => 'success',
            self::ENCERRADO => 'error',
            self::EM_BREVE => 'primary',
            self::EM_ANDAMENTO => 'warning',
        };
    }
}
