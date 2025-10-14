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
            $table->char('mileage_out_stock',20)->nullable()->comment('Kilometraje de salida del almacen')->after('provider_observation');
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
            $table->dropColumn([
                'mileage_out_stock'
            ]);
        });
    }
};
