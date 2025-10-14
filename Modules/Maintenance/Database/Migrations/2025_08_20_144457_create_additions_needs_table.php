<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mant_sn_additions_needs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->char('need', 11)->nullable();
            $table->string('description', 300)->nullable();
            $table->char('unit_measurement', 40)->nullable()->comment('UNIDAD DE MEDIDA');
            $table->integer('unit_value')->nullable()->comment('VALOR UNITARIO');
            $table->integer('iva')->nullable()->comment('IVA DEL VALOR UNITARIO');
            $table->integer('amount_requested')->nullable()->comment('CANTIDAD SOLICITADA');
            $table->integer('total_value')->nullable()->comment('VALOR TOTAL');
            $table->char('maintenance_type', 10)->nullable()->comment('TIPO DE MANTENIMIENTO');
            $table->integer('valor_total')->nullable()->comment('VALOR TOTAL');
            // Apunta a la tabla mant_sn_additions_spare_parts_activities (id)
            $table->unsignedBigInteger('addition_id')->nullable()->comment('ID DE LA ADICION (actividad)');

            $table->tinyInteger('is_approved')->nullable()->comment('ESTA APROBADO');
            $table->tinyInteger('is_returned')->nullable()->comment('ESTA DEVUELTO');

            $table->timestamps();
            $table->softDeletes();

            // FK hacia mant_sn_additions_spare_parts_activities.id
            $table->foreign('addition_id', 'fk_needs_activity')
                ->references('id')
                ->on('mant_sn_additions_spare_parts_activities')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        // Primero eliminar la FK si existe
        Schema::table('mant_sn_additions_needs', function (Blueprint $table) {
            $table->dropForeign(['addition_id']);
        });

        Schema::dropIfExists('mant_sn_additions_needs');
    }
};
