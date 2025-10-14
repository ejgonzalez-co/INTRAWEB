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
            $table->boolean('no_factura')->default(0)->after('numero_factura')
                  ->comment('1 = Aún no tengo el número de la factura, 0 = Sí tiene factura');
        });
    }

    public function down()
    {
        Schema::table('mant_sn_orders', function (Blueprint $table) {
            $table->dropColumn('no_factura');
        });
    }
};
