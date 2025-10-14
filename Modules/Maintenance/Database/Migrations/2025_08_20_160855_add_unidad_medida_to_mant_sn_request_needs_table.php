<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 🔹 Tabla mant_sn_request_needs
        Schema::table('mant_sn_request_needs', function (Blueprint $table) {
            $table->string('unidad_medida_conversion', 25)->nullable()->after('id');
            $table->string('cantidad_solicitada_conversion', 255)->nullable()->after('unidad_medida_conversion');
        });

        // 🔹 Tabla mant_sn_orders_has_needs
        Schema::table('mant_sn_orders_has_needs', function (Blueprint $table) {
            $table->string('unidad_medida_conversion', 25)->nullable()->after('cantidad_entrada');
            $table->string('cantidad_solicitada_conversion', 255)->nullable()->after('unidad_medida_conversion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mant_sn_request_needs', function (Blueprint $table) {
            $table->dropColumn(['unidad_medida_conversion','cantidad_solicitada_conversion']);
        });

        Schema::table('mant_sn_orders_has_needs', function (Blueprint $table) {
            $table->dropColumn(['unidad_medida_conversion','cantidad_solicitada_conversion']);
        });
    }
};