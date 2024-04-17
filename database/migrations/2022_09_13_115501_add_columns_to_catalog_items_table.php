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
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->integer("country_id")->index()->nullable();
            $table->string("country_title")->nullable();
            $table->integer("city_id")->index()->nullable();
            $table->string("city_title")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->dropColumn("country_id");
            $table->dropColumn("country_title");
            $table->dropColumn("city_id");
            $table->dropColumn("city_title");
        });
    }
};
