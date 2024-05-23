<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCheckListsTechnicianIdForeignFromCheckLists extends Migration
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
            $table->dropForeign('check_lists_ibfk_2');
            $table->dropIndex('check_lists_technician_id_foreign');
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
            $table->foreign('technician_id')->references('id')->on('users');
        });
    }
}
