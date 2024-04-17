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
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name_ru")->nullable();
            $table->integer("catalog_id")->index()->default(0);
            $table->integer("user_id")->index()->default(0);
            $table->float("price")->default(0);
            $table->text("image")->nullable();
            $table->text("body_ru")->nullable();
            $table->integer("status")->default(0);
            $table->text("status_text")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_items');
    }
};
