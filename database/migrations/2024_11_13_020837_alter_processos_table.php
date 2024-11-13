<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->datetime('data_recurso_inicio')->after('data_suspensao_integral')->nullable();
            $table->datetime('data_recurso_fim')->after('data_recurso_inicio')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('processos', function (Blueprint $table) {

        });
    }
};
