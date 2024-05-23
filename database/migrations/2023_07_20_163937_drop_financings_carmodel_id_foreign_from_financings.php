<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFinancingsCarmodelIdForeignFromFinancings extends Migration
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
            $table->dropForeign('financings_ibfk_2');
            $table->dropIndex('financings_carmodel_id_foreign');
            $table->dropColumn('carmodel_id');
            $table->string('carmodel_name')->after('brand_name');
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
            $table->dropColumn('carmodel_name');
            $table->unsignedBigInteger('carmodel_id');
            $table->foreign('carmodel_id')->references(‘id’)->on('carmodels');
        });
    }
}
