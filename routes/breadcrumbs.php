<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('despesas.despesas.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Despesas', route('despesas.despesas.index'));
});

Breadcrumbs::for('despesas.despesas.diarias', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.despesas.index');
    $trail->push('Despesas com Diárias', route('despesas.despesas.diarias'));
});

Breadcrumbs::for('despesas.despesas.obras', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.despesas.index');
    $trail->push('Despesas com Obras', route('despesas.despesas.obras'));
});

Breadcrumbs::for('despesas.despesas.passagens', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.despesas.index');
    $trail->push('Despesas com Passagens', route('despesas.despesas.passagens'));
});

Breadcrumbs::for('despesas.despesas.show', function (BreadcrumbTrail $trail): void {
    if (request()->route('despesa')->tipo === 'Diária') {
        $trail->parent('despesas.despesas.diarias');
    } elseif (request()->route('despesa')->tipo === 'Obra') {
        $trail->parent('despesas.despesas.obras');
    } elseif (request()->route('despesa')->tipo === 'Passagem') {
        $trail->parent('despesas.despesas.passagens');
    } else {
        $trail->parent('despesas.despesas.index');
    }
    $trail->push(
        'Despesa: '.request()->route('despesa')->tipo.' - '.request()->route('despesa')->numero,
        route('despesas.despesas.show', request()->route('despesa')),
    );
});

Breadcrumbs::for('despesas.compras.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Licitações/Compras', route('despesas.compras.index'));
});

Breadcrumbs::for('despesas.compras.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.compras.index');
    $trail->push(
        'Licitação: '.request()->route('licitacao')->edital.'-'.request()->route('licitacao')->processo,
        route('despesas.compras.show', request()->route('licitacao')),
    );
});

Breadcrumbs::for('despesas.despesas.programas-e-acoes.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.despesas.index');
    $trail->push('Programas e Ações', route('despesas.despesas.programas-e-acoes.index'));
});

Breadcrumbs::for('despesas.despesas.programas-e-acoes.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.despesas.programas-e-acoes.index');
    $trail->push(request()->route('programa')->atividade_projeto, route('despesas.despesas.programas-e-acoes.show', request()->route('programa')));
});

Breadcrumbs::for('contratos.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Contratos', route('contratos.index'));
});

Breadcrumbs::for('contratos.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('contratos.index');
    $trail->push('Contrato: '.request()->route('contrato')->numero, route('contratos.show', request()->route('contrato')));
});

Breadcrumbs::for('despesas.atas.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Atas de Registro de Preço', route('despesas.atas.index'));
});

Breadcrumbs::for('despesas.atas.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('despesas.atas.index');
    $trail->push(request()->route('ata')->titulo, route('despesas.atas.show', request()->route('ata')));
});

Breadcrumbs::for('receitas.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Receitas', route('receitas.index'));
});

Breadcrumbs::for('receitas.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('receitas.index');
    $trail->push(request()->route('receita')->nrd_documento, route('receitas.show', request()->route('receita')));
});

Breadcrumbs::for('receitas.receitas.previstas', function (BreadcrumbTrail $trail): void {
    $trail->parent('receitas.index');
    $trail->push('Receitas Previstas', route('receitas.previstas'));
});

Breadcrumbs::for('receitas.dividas-ativas', function (BreadcrumbTrail $trail): void {
    $trail->parent('receitas.index');
    $trail->push('Dívidas Ativas', route('receitas.dividas-ativas'));
});

Breadcrumbs::for('receitas.renuncias', function (BreadcrumbTrail $trail): void {
    $trail->parent('receitas.index');
    $trail->push('Renúncias de Receitas', route('receitas.renuncias'));
});

Breadcrumbs::for('receitas.isencoes', function (BreadcrumbTrail $trail): void {
    $trail->parent('receitas.index');
    $trail->push('Isenções e Imunidades', route('receitas.isencoes'));
});

Breadcrumbs::for('obras.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Obras', route('obras.index'));
});

Breadcrumbs::for('obras.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('obras.index');
    $trail->push(request()->route('obra')->nome, route('obras.show', request()->route('obra')));
});

Breadcrumbs::for('denuncia', function (BreadcrumbTrail $trail): void {
    $trail->push('Canal de Denúncias', route('denuncia'));
});

Breadcrumbs::for('denuncia.enviado', function (BreadcrumbTrail $trail): void {
    $trail->parent('denuncia');
});

Breadcrumbs::for('arquivos.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Arquivos', route('arquivos.index'));
});

Breadcrumbs::for('pages.mapa-do-site', function (BreadcrumbTrail $trail): void {
    $trail->push('Mapa do Site', route('pages.mapa-do-site'));
});

Breadcrumbs::for('pages.legislacao', function (BreadcrumbTrail $trail): void {
    $trail->push('Legislação', route('pages.legislacao'));
});

Breadcrumbs::for('pages.glossario', function (BreadcrumbTrail $trail): void {
    $trail->push('Glossário', route('pages.glossario'));
});

Breadcrumbs::for('paineis-bi', function (BreadcrumbTrail $trail): void {
    $trail->push('Painel');
    $trail->push(Illuminate\Support\Str::upper(request()->route('painel')), route('paineis-bi', request()->route('painel')));
});
