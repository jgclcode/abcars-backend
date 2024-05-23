<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCheckListsUserIdForeignFromCheckLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_lists', function (Blueprint $table) {
            //
            // $table->dropForeign('check_lists_check_lists_ibfk_1_foreign');
            $table->dropForeign('check_lists_ibfk_1');
            // $table->dropForeign('check_lists_user_id_foreign');
            $table->dropIndex('check_lists_user_id_foreign');
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
            //
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
