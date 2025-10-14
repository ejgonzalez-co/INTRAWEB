<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitarDescargaDocumentosToEePermisoUsuariosExpendientesTable extends Migration
{
    public function up()
    {
        Schema::table('ee_permiso_usuarios_expendientes', function (Blueprint $table) {
            $table->tinyInteger('limitar_descarga_documentos')
                  ->default(0)
                  ->after('permiso');
        });
    }

    public function down()
    {
        Schema::table('ee_permiso_usuarios_expendientes', function (Blueprint $table) {
            $table->dropColumn('limitar_descarga_documentos');
        });
    }
}
