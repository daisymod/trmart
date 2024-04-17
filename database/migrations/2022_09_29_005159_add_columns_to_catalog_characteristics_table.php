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
            $table->string("name_tr")->nullable();
            $table->string("name_kz")->nullable();
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
            $table->dropColumn("name_tr");
            $table->dropColumn("name_kz");
        });
    }
};
