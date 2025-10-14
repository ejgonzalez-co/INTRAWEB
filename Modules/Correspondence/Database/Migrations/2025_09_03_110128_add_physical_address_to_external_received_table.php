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
        Schema::table('external_received', function (Blueprint $table) {
            $table->string('physical_address', 100)
                ->nullable()
                ->comment('Direccion fisica del ciudadano');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_received', function (Blueprint $table) {
            $table->dropColumn('physical_address');
        });
    }
};
