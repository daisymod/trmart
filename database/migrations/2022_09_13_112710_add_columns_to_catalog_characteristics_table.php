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
        Schema::table('catalog_characteristics', function (Blueprint $table) {
            $table->enum("basic", ["Y", "N"])->default("N")->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_characteristics', function (Blueprint $table) {
            $table->dropColumn("basic");
        });
    }
};
