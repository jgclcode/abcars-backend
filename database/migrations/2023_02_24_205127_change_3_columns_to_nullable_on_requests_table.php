<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Change3ColumnsToNullableOnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            //
            $table->integer('amount_pay')->nullable()->change();
        });

        \DB::statement("ALTER TABLE `requests` CHANGE `release` `release` ENUM('inmediatamente', 'en un mes', 'en tres meses', 'en seis meses', 'N/A') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A';");

        \DB::statement("ALTER TABLE `requests` CHANGE `type_purchase` `type_purchase` ENUM('contado', 'financiamiento', 'N/A') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            //
            $table->integer('amount_pay')->nullable(false)->change();
        });

        \DB::statement("ALTER TABLE `requests` CHANGE `release` `release` ENUM('inmediatamente', 'en un mes', 'en tres meses', 'en seis meses') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");

        \DB::statement("ALTER TABLE `requests` CHANGE `type_purchase` `type_purchase` ENUM('contado', 'financiamiento') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    }
}
