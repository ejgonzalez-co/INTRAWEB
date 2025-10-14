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
        Schema::table('ee_expediente', function (Blueprint $table) {
            // Eliminar el campo 'permiso_usar_expedientes_todas'
            $table->dropColumn('permiso_usar_expedientes_todas');

            // Renombrar el campo 'permiso_consultar_expedientes_todas' a 'permiso_general_expediente'
            $table->renameColumn('permiso_consultar_expedientes_todas', 'permiso_general_expediente');
        });

        // Cambiar el tipo del nuevo campo a string(150)
        Schema::table('ee_expediente', function (Blueprint $table) {
            $table->string('permiso_general_expediente', 150)->nullable()->change();
        });
        
        // Tabla de historial
        Schema::table('ee_expediente_historial', function (Blueprint $table) {
            // Eliminar el campo 'permiso_usar_expedientes_todas'
            $table->dropColumn('permiso_usar_expedientes_todas');

            // Renombrar el campo 'permiso_consultar_expedientes_todas' a 'permiso_general_expediente'
            $table->renameColumn('permiso_consultar_expedientes_todas', 'permiso_general_expediente');
        });

        // Cambiar el tipo del nuevo campo a string(150) en la tabla historial
        Schema::table('ee_expediente_historial', function (Blueprint $table) {
            $table->string('permiso_general_expediente', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restaurar el nombre y tipo original del campo
        Schema::table('ee_expediente', function (Blueprint $table) {
            // Cambiar tipo de vuelta si era diferente
            $table->boolean('permiso_general_expediente')->change();

            // Renombrar de vuelta al nombre original
            $table->renameColumn('permiso_general_expediente', 'permiso_consultar_expedientes_todas');

            // Restaurar el campo eliminado
            $table->boolean('permiso_usar_expedientes_todas')->default(false);
        });

        // Restaurar el nombre y tipo original del campo en la tabla historial
        Schema::table('ee_expediente_historial', function (Blueprint $table) {
            // Cambiar tipo de vuelta si era diferente
            $table->boolean('permiso_general_expediente')->change();

            // Renombrar de vuelta al nombre original
            $table->renameColumn('permiso_general_expediente', 'permiso_consultar_expedientes_todas');

            // Restaurar el campo eliminado
            $table->boolean('permiso_usar_expedientes_todas')->default(false);
        });
    }
};
