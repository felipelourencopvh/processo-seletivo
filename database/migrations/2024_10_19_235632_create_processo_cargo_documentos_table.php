<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('processo_cargo_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('documento');
            $table->boolean('obrigatorio');
            $table->unsignedInteger('quantidade_minima');
            $table->unsignedInteger('quantidade_maxima');
            $table->double('pontuacao')->nullable();
            $table->unsignedInteger('ordem')->nullable();
            $table->foreignId('processo_cargo_id')->constrained();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('processo_cargo_documentos');
    }
};
