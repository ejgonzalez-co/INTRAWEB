<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ht_tic_equipment_purchase_details', function (Blueprint $table) {
             $table->date('warranty_termination_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ht_tic_equipment_purchase_details', function (Blueprint $table) {
            $table->dateTime('warranty_termination_date')->change();
        });
    }
};
