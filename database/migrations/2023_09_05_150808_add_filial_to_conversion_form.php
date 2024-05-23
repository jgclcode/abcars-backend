<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilialToConversionForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversion__forms', function (Blueprint $table) {
            //
            $table->string('filial')->after('appointment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversion__forms', function (Blueprint $table) {
            //
            $table->dropColumn('filial');
        });
    }
}
