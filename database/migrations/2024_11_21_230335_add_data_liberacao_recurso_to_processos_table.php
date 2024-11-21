<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->datetime('data_liberacao_recurso')->after('data_recurso_fim')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('processos', function (Blueprint $table) {

        });
    }
};
