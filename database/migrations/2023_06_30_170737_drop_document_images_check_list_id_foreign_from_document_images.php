<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDocumentImagesCheckListIdForeignFromDocumentImages extends Migration
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
            $table->dropForeign('document_images_ibfk_1');
            $table->dropIndex('document_images_check_list_id_foreign');
            $table->dropColumn('check_list_id');
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
        });
    }
}
