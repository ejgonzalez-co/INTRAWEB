<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mant_sn_additions_spare_parts_activities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->char('type_request', 26)->nullable()->comment('TIPO DE SOLICITUD');
            $table->char('total_solicitud', 20)->nullable();

            $table->longText('admin_observation')->nullable()->comment('OBSERVACION DEL ADMIN');
            $table->longText('provider_observation')->nullable()->comment('OBSERVACION PROVEEDOR');

            $table->timestamps();
            $table->softDeletes();

            // 🔹 Claves foráneas con tipos correctos
            $table->bigInteger('order_id')->comment('ID DE LA ORDEN');
            $table->bigInteger('request_id')->comment('ID DE LA SOLICITUD DE NECESIDAD');
            $table->char('status', 32)->nullable()->comment('ESTADO DE LA ADICION');
            $table->unsignedBigInteger('admin_creator_id')->nullable()->comment('ID DEL ADMIN CREADOR');
            $table->Integer('provider_creator_id')->nullable()->comment('ID DEL PROVEEDOR CREADOR');

            // 🔹 Definir FKs
            $table->foreign('order_id', 'fk_additions_orders')
                ->references('id')->on('mant_sn_orders')
                ->onDelete('cascade');

            $table->foreign('request_id', 'fk_additions_requests')
                ->references('id')->on('mant_sn_request')
                ->onDelete('cascade');

            $table->foreign('admin_creator_id', 'fk_additions_admins')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('provider_creator_id', 'fk_additions_providers')
                ->references('id')->on('mant_providers')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mant_sn_additions_spare_parts_activities');
    }
};
