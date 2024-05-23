<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuyTypeNullableToSheetQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_quotes', function (Blueprint $table) {
            //
            $table->string('buyType')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheet_quotes', function (Blueprint $table) {
            //
            $table->string('buyType')->nullable(false)->change();
        });
    }
}
