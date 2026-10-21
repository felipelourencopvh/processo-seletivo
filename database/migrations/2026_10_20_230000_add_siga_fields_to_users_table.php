<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Campos para o login único (SIGA): o CPF reconhece a pessoa que entra pelo SIGA e o "ativo"
 * permite suspender o acesso ao backoffice sem apagar o usuário. Nulo em candidato (não usa SIGA).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 11)->nullable()->unique()->after('email');
            $table->boolean('ativo')->default(true)->after('cpf');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropColumn(['cpf', 'ativo']);
        });
    }
};
