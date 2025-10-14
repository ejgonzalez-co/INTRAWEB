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
            $table->timestamp('date_supervisor_submission')
                  ->nullable()
                  ->comment('Fecha envio del supervisor');
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
            $table->dropColumn('date_supervisor_submission');
        });
    }
};
