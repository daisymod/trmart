<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_copies', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->integer('catalog_item_id')->nullable();
            $table->text('image')->nullable();
            $table->string('name')->nullable();
            $table->string('article')->nullable();
            $table->string('size')->nullable();
            $table->integer('count')->nullable();
            $table->double('price')->nullable();
            $table->string('color')->nullable();
            $table->double('price_tenge')->nullable();
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
        Schema::dropIfExists('order_item_copies');
    }
};
