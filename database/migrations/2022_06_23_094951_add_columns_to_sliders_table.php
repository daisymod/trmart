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
        Schema::table('sliders', function (Blueprint $table) {
            $table->enum("type", ["news", "stock", "discount"])->nullable();
            $table->timestamp("dt_start")->nullable();
            $table->timestamp("dt_end")->nullable();
            //$table->enum("active", ["Y", "N"])->default("Y");
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
