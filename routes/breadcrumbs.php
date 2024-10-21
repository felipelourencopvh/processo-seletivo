<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('processos-seletivos.show', function (BreadcrumbTrail $trail): void {
    $trail->push(request()->route('processo')->titulo, route('processos-seletivos.show', request()->route('processo')));
});

Breadcrumbs::for('processos-seletivos.inscrever', function (BreadcrumbTrail $trail): void {
    $trail->parent('processos-seletivos.show');
    $trail->push('Inscrever-se');
});

Breadcrumbs::for('candidato.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Painel do Candidato', route('candidato.index'));
});

Breadcrumbs::for('candidato.edit', function (BreadcrumbTrail $trail): void {
    $trail->parent('candidato.index');
    $trail->push('Editar');
});
