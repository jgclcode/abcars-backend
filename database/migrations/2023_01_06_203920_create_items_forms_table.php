<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_item', function (Blueprint $table) {
            $table->id();        
            
            $table->unsignedBigInteger('form_id');
            $table->foreign('form_id')
              ->references('id')->on('forms');
            
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')
              ->references('id')->on('items');

            $table->string('value')->nullable();

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
        Schema::dropIfExists('form_item');
    }
}
