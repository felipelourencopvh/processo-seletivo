<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('situacao')->index();
            $table->string('cota')->nullable();
            $table->double('pontuacao')->nullable();
            $table->double('pontuacao_previa')->nullable();
            $table->foreignId('processo_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('cargo_id')->constrained('processo_cargos');
            $table->timestamps();
        });

        Schema::create('inscricao_documentos', function (Blueprint $table) {
            $table->id();
            $table->tinyText('documento');
            $table->boolean('enviado')->default(false);
            $table->boolean('aprovado')->default(false);
            $table->double('pontuacao')->nullable();
            $table->double('pontuacao_previa')->nullable();
            $table->foreignUuid('inscricao_id')->constrained('inscricoes');
            $table->foreignId('processo_cargo_documento_id')->constrained('processo_cargo_documentos');
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
        Schema::dropIfExists('inscricao_documentos');
    }
};
