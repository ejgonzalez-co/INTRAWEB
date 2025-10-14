<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eliminar tablas si existen
        Schema::dropIfExists('ee_permiso_usar_expediente');
        Schema::dropIfExists('ee_permiso_consultar_expediente');
        
        // Crea la tabla que almacena los permisos de los usuarios sobre un expediente
        Schema::create('ee_permiso_usuarios_expendientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 255);
            $table->integer('dependencia_usuario_id')->nullable();
            $table->string('tipo', 50)->nullable()->comment('Usuario/Dependencia');
            $table->string('tipo_usuario', 50)->nullable()->comment('Interno/Externo');
            $table->string('correo', 255)->nullable()->index();
            $table->string('pin_acceso', 255)->nullable();
            $table->string('permiso', 100);
            $table->unsignedBigInteger('ee_expedientes_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Revert the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Elimina la tabla que almacena los permisos de los usuarios sobre un expediente
        Schema::dropIfExists('nombre_de_la_tabla');
    }
};
