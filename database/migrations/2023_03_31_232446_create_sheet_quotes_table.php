<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheet_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('body');
            $table->string('brand');
            $table->string('model');
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->bigInteger('phone');
            $table->string('buyType');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheet_quotes');
    }
}
