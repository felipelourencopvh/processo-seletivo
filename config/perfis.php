<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Perfis do Recrutamento no SIGA
|--------------------------------------------------------------------------
|
| O SIGA lê esta lista (GET /siga/api/perfis) para dar acesso às pessoas. "papel" é o papel do
| Filament Shield que o perfil concede; "alcance" = o perfil vale num processo seletivo (o gestor
| só vê os processos que lhe forem dados); sem alcance, vale em todos.
|
*/

return [
    'administrador' => [
        'nome' => 'Administrador do Recrutamento',
        'descricao' => 'Cria e administra todos os processos seletivos, os usuários e as permissões (backoffice /admin).',
        'papel' => 'super_admin',
        'alcance' => false,
    ],
    'gestor' => [
        'nome' => 'Gestor de processo seletivo',
        'descricao' => 'Conduz os processos seletivos que lhe forem dados: inscrições, classificação, recursos e resultado (/app).',
        'papel' => 'admin',
        'alcance' => true,
    ],
];
