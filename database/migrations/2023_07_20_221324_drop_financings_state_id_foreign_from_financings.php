<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFinancingsStateIdForeignFromFinancings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financings', function (Blueprint $table) {
            //
            $table->dropForeign('financings_ibfk_3');
            $table->dropIndex('financings_state_id_foreign');
            $table->dropColumn('state_id');
            $table->string('state_name')->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financings', function (Blueprint $table) {
            //
            $table->dropColumn('state_name');  
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');
        });
    }
}
