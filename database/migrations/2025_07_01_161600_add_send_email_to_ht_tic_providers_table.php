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
        Schema::table('ht_tic_provider', function (Blueprint $table) {
            $table->boolean('sendEmail')->default(0); // coloca el campo después de 'nombre' (ajústalo si quieres)
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ht_tic_providers', function (Blueprint $table) {
            //
        });
    }
};
