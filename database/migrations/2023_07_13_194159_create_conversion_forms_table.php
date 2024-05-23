<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversion__forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastName');
            $table->bigInteger('phone');
            $table->string('email');
            $table->timestamp('appointment');
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
        Schema::dropIfExists('conversion__forms');
    }
}
