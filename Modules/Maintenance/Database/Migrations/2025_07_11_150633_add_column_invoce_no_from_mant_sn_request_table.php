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
            $table->char('invoice_no',40)->nullable()->comment('Numero de la factura')->after('approval_justification');
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
            $table->dropColumn([
                'invoice_no'
            ]);
        });
    }
};
