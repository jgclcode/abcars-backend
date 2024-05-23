<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCompraGuiaAColumnOnDocumentImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_images', function (Blueprint $table) {
            $table->dropColumn(['compraGuiaA', 'ventaGuiaA', 'compraIntelimotors', 'ventaIntelimotors']);
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
            $table->integer('compraGuiaA')->after('copiaGuiaAutometrica');
            $table->integer('ventaGuiaA')->after('compraGuiaA');
            $table->integer('compraIntelimotors')->after('consultaIntelimotors');
            $table->integer('ventaIntelimotors')->after('compraIntelimotors');
        });
    }
}
