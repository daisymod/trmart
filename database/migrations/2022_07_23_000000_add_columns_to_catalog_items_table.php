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
        Schema::table("catalog_items", function (Blueprint $table) {
            $table->string("brand")->nullable();
            $table->string("country")->nullable();
            $table->string("barcode")->nullable();
            $table->string("equipment")->nullable();
            $table->string("composition")->nullable();
            $table->string("color")->nullable();
            $table->string("size")->nullable();
            $table->string("article")->nullable();
            $table->string("characteristics")->nullable();
            $table->string("discount")->nullable();
            $table->string("weight")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn("active");
            $table->dropColumn("dt_start");
            $table->dropColumn("dt_end");
        });
    }
};
