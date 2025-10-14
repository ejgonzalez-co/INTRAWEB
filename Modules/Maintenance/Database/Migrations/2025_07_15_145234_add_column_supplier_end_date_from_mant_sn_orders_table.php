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
        Schema::table('mant_sn_orders', function (Blueprint $table) {
            $table->date('supplier_end_date')
                  ->nullable()
                  ->comment('Fecha finalizacion del proveedor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mant_sn_orders', function (Blueprint $table) {
            $table->dropColumn('supplier_end_date');
        });
    }
};
