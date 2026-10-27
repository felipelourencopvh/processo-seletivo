<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A inscrição usa UUID e é registrada no activity_log: com subject_id inteiro (nullableMorphs), o MySQL em modo estrito
 * recusava a gravação e a inscrição do candidato dava erro. subject_id passa a texto (cabe inteiro e UUID).
 */
return new class () extends Migration {
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->string('subject_id', 36)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->change();
        });
    }
};
