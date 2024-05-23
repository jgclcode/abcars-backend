<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVinNullableToVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            //
            $table->dropForeign(['vehicle_vin']);
        });
        
        Schema::table('vehicles', function (Blueprint $table) {
            //
            $table->dropUnique(['vin']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('vehicles', function (Blueprint $table) {
            //
            $table->unique(['vin']);
        });
        
        Schema::table('forms', function (Blueprint $table) {
            //
            $table->foreign('vehicle_vin')->references('vin')->on('vehicles');
        });
    }
}
