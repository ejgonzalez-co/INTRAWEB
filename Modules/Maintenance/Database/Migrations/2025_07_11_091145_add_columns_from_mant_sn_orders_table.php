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
    public function up(): void
    {
        Schema::table('mant_sn_orders', function (Blueprint $table) {
            $table->date('date_entry')->nullable()->after('id');
            $table->date('date_work_completion')->nullable()->comment('Fecha de finalizacion del trabajo')->after('date_entry');
            $table->longText('url_evidences')->nullable()->comment('Ruta de las evidencias')->after('date_work_completion');
            $table->longText('provider_observation')->nullable()->comment('Observacion del proveedor')->after('url_evidences');
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
                'date_entry',
                'date_work_completion',
                'url_evidences',
                'provider_observation'
            ]);
        });
    }
};
