<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_characteristic_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("position")->default(0)->index();
            $table->string("name_ru")->nullable();
            $table->integer("catalog_characteristic_id")->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_characteristic_items');
    }
};
