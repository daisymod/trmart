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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer("region_id")->after('country_name')->nullable();
            $table->string("region_name")->after('region_id')->nullable();
            $table->integer("area_id")->after('region_name')->nullable();
            $table->string("area_name")->after('area_id')->nullable();
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("region_id");
            $table->dropColumn("region_name");
            $table->dropColumn("area_id");
            $table->dropColumn("area_name");
            $table->dropColumn("postcode_id");
        });
    }
};
