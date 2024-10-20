<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('documento')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('rg')->nullable();
            $table->string('filiacao_mae')->nullable();
            $table->string('filiacao_pai')->nullable();
            $table->string('telefone_residencial')->nullable();
            $table->string('telefone_celular')->nullable();
            $table->string('endereco_logradouro')->nullable();
            $table->string('endereco_numero')->nullable();
            $table->string('endereco_complemento')->nullable();
            $table->string('endereco_bairro')->nullable();
            $table->string('endereco_cep')->nullable();
            $table->string('endereco_cidade')->nullable();
            $table->string('endereco_uf')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
