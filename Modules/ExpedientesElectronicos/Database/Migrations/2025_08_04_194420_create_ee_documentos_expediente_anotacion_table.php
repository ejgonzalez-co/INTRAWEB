<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEeDocumentosExpedienteAnotacionTable extends Migration
{
    public function up()
    {
        Schema::create('ee_documentos_expediente_anotacion', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nombre_usuario', 255)
                ->nullable()
                ->comment('Nombre del usuario que creo el registro');

            $table->longText('anotacion')->nullable();

            $table->integer('vigencia');

            $table->unsignedBigInteger('ee_documentos_expediente_id');
            $table->unsignedBigInteger('users_id')
                ->comment('ID del usuario que creo el registro');

            $table->text('attached')->nullable();
            $table->text('leido_por')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('ee_documentos_expediente_id', 'ee_doc_expediente_id');
            $table->index('users_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ee_documentos_expediente_anotacion');
    }
}
