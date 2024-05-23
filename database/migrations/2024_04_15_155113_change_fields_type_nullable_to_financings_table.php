<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsTypeNullableToFinancingsTable extends Migration
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
            $table->string('street_name_reference')->nullable()->change();
            $table->string('suburb_reference')->nullable()->change();
            $table->string('number_reference')->nullable()->change();
            $table->string('postal_code_reference')->nullable()->change();
            $table->string('state_reference')->nullable()->change();
            $table->string('municipality_reference')->nullable()->change();
            $table->string('credit_card')->nullable()->change();
            $table->string('mortgage_credit')->nullable()->change();
            $table->string('automotive_credit')->nullable()->change();
            $table->string('third_person')->nullable()->change();
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
            $table->string('street_name_reference')->nullable(false)->change();
            $table->string('suburb_reference')->nullable(false)->change();
            $table->string('number_reference')->nullable(false)->change();
            $table->string('postal_code_reference')->nullable(false)->change();
            $table->string('state_reference')->nullable(false)->change();
            $table->string('municipality_reference')->nullable(false)->change();
            $table->string('credit_card')->nullable(false)->change();
            $table->string('mortgage_credit')->nullable(false)->change();
            $table->string('automotive_credit')->nullable(false)->change();
            $table->string('third_person')->nullable(false)->change();
        });
    }
}
