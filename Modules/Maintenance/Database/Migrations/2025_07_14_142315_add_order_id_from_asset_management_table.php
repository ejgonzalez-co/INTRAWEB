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
        Schema::table('mant_asset_management', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable()->comment('Id de la orden');
            $table->foreign('order_id', 'mant_asset_management_mant_sn_orders_FK')
                ->references('id')
                ->on('mant_sn_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mant_asset_management', function (Blueprint $table) {
            $table->dropForeign('mant_asset_management_mant_sn_orders_FK');
            $table->dropColumn('order_id');
        });
    }
};
