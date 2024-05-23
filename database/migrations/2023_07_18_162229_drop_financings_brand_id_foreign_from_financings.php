<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFinancingsBrandIdForeignFromFinancings extends Migration
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
            $table->dropForeign('financings_ibfk_1');
            $table->dropIndex('financings_brand_id_foreign');
            $table->dropColumn('brand_id');
            $table->string('brand_name')->after('year');
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
            $table->dropColumn('brand_name');
            $table->unsignedBigInteger('brand_id')->after('year');
            $table->foreign('brand_id')->references('id')->on('brands');
        });
    }
}
