<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Agrega los campos type_provider y firma_proveedor a la tabla mant_providers.
 *
 * @author DEVELOPER LARAVEL Y VUEJS
 * @version 1.2.0
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mant_providers', function (Blueprint $table) {
            // Se agrega el campo después de la columna 'nit' para un orden lógico.
            $table->enum('type_provider', ['Interno', 'Externo'])
                  ->comment('Define el tipo de bien o servicio que ofrece el proveedor.')
                  ->after('state');

            // Se agrega el campo después de la columna 'address'.
            $table->string('firma_proveedor')
                  ->nullable()
                  ->comment('Ruta del archivo de la imagen de la firma del proveedor.')
                  ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mant_providers', function (Blueprint $table) {
            // Elimina las columnas si se revierte la migración.
            $table->dropColumn(['type_provider', 'firma_proveedor']);
        });
    }
};