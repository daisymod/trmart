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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("order_id")->index();
            $table->integer("user_id")->index();
            $table->string("user_name")->nullable();
            $table->integer("catalog_item_id")->index();
            $table->text("image")->nullable();
            $table->string("name")->nullable();
            $table->string("article")->nullable();
            $table->string("size")->nullable();
            $table->integer("count")->nullable();
            $table->float("price")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
