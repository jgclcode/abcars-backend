<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFolioNumberAndFullNameReferringToSheetQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_quotes', function (Blueprint $table) {
            $table->string('folioNumber')->nullable()->after('clientPriceOffer');
            $table->string('fullNameReferring')->nullable()->after('folioNumber');
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
            $table->dropColumn('folioNumber');
            $table->dropColumn('fullNameReferring');
        });
    }
}
