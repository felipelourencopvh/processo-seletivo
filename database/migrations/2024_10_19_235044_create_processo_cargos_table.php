<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('processo_cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nivel')->nullable();
            $table->string('localidade')->nullable();
            $table->string('descricao')->nullable();
            $table->unsignedInteger('vagas_ampla')->nullable();
            $table->unsignedInteger('vagas_pcd')->nullable();
            $table->unsignedInteger('vagas_ppp')->nullable();
            $table->foreignId('processo_id')->constrained();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('processo_cargos');
    }
};
