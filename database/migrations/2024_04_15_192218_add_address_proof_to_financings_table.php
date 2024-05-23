<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressProofToFinancingsTable extends Migration
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
            $table->string('address_proof')->nullable()->after('ine_back');
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
            $table->dropColumn('address_proof');
        });
    }
}
