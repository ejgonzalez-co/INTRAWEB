<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('de_documento', function (Blueprint $table) {
            $table->string('require_answer', 45)->nullable();
            $table->string('tipo_finalizacion', 255)->nullable();
            $table->unsignedBigInteger('pqr_id')->nullable();
            $table->string('pqr_consecutive', 45)->nullable();
        });

        Schema::table('de_documento_historial', function (Blueprint $table) {
            $table->string('require_answer', 45)->nullable();
            $table->string('tipo_finalizacion', 255)->nullable();
            $table->unsignedBigInteger('pqr_id')->nullable();
            $table->string('pqr_consecutive', 45)->nullable();
        });

        Schema::table('pqr', function (Blueprint $table) {
            $table->string('de_documento_id', 20)->nullable()->comment('ID del documento electrónico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('de_documento', function (Blueprint $table) {
            $table->dropColumn(['require_answer', 'tipo_finalizacion', 'pqr_id', 'pqr_consecutive']);
        });

        Schema::table('de_documento_historial', function (Blueprint $table) {
            $table->dropColumn(['require_answer', 'tipo_finalizacion', 'pqr_id', 'pqr_consecutive']);
        });

        Schema::table('pqr', function (Blueprint $table) {
            $table->dropColumn('de_documento_id');
        });
    }
};
