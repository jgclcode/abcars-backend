<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubsidiaryToSellYourCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sell_your_cars', function (Blueprint $table) {
            //
            $table->enum('subsidiary', ['puebla', 'tlaxcala', 'pachuca'])->nullable()->after('estimated_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sell_your_cars', function (Blueprint $table) {
            //
            $table->dropColumn('subsidiary');
        });
    }
}
