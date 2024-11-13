<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->text('recurso')->after('pontuacao_previa')->nullable();
            $table->text('analise_recurso')->after('recurso')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {

        });
    }
};
