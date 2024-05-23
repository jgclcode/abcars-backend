<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellYourCarIdToDocumentImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_images', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('sell_your_car_id')->nullable();
            $table->foreign('sell_your_car_id')->references('id')->on('sell_your_cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_images', function (Blueprint $table) {
            //
            $table->dropForeign('document_images_sell_your_car_id_foreign');
            $table->dropColumn('sell_your_car_id');
        });
    }
}
