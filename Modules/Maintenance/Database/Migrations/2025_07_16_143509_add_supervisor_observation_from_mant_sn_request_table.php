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
            $table->longText('supervisor_observation')
                  ->nullable()
                  ->comment('Observación del supervisor');
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
            $table->dropColumn('supervisor_observation');
        });
    }
};
