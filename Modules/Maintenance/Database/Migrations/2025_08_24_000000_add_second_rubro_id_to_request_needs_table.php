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
        Schema::table('mant_sn_request', function (Blueprint $table) {
            // 🔹 ID del segundo rubro (varchar 255 por defecto)
            $table->string('second_rubro_id', 255)->nullable()->after('rubro_id');

            // 🔹 Nombre del segundo rubro (varchar 500)
            $table->string('second_rubro_nombre', 500)->nullable()->after('second_rubro_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mant_sn_request', function (Blueprint $table) {
            $table->dropColumn(['second_rubro_id', 'second_rubro_nombre']);
        });
    }
};