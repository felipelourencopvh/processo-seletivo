<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug');
            $table->text('descricao')->nullable();
            $table->dateTime('data_abertura')->nullable();
            $table->date('data_resultado')->nullable();
            $table->dateTime('data_suspensao_parcial')->nullable();
            $table->dateTime('data_suspensao_integral')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('processo_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('processos');
        Schema::dropIfExists('processo_user');
    }
};
