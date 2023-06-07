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
        Schema::table('users', function (Blueprint $table) {
            $table->integer("region_id")->after('country_title')->nullable();
            $table->string("region_title")->after('region_id')->nullable();
            $table->integer("area_id")->after('region_title')->nullable();
            $table->string("area_title")->after('area_id')->nullable();
            $table->integer("postcode_id")->after('postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("region_id");
            $table->dropColumn("region_title");
            $table->dropColumn("area_id");
            $table->dropColumn("area_title");
            $table->dropColumn("postcode_id");
        });
    }
};
