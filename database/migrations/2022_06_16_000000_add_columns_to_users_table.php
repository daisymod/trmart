<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->timestamp("dt_reg")->nullable();
            $table->integer("country_id")->index()->nullable();
            $table->string("country_title")->nullable();
            $table->integer("city_id")->index()->nullable();
            $table->string("city_title")->nullable();
            $table->float("rating")->nullable();
            $table->integer("position")->nullable();
        });
        DB::statement("UPDATE users SET position = id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("dt_reg");
            $table->dropColumn("country_id");
            $table->dropColumn("country_title");
            $table->dropColumn("city_id");
            $table->dropColumn("city_title");
            $table->dropColumn("rating");
        });
    }
};
