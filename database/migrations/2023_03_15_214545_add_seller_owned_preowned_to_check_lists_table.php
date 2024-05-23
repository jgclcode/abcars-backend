<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellerOwnedPreownedToCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_lists', function (Blueprint $table) {
            $table->string('seller');
            $table->string('ownedPreowned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_lists', function (Blueprint $table) {
            $table->dropColumn('seller');
            $table->dropColumn('ownedPreowned');
        });
    }
}
