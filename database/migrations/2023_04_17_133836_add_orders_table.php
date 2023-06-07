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
            $table->integer("article")->after('postcode')->nullable();
            $table->string("merchant")->after('article')->nullable();
            $table->integer("merchant_id")->after('merchant')->nullable();
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
            $table->dropColumn("article");
            $table->dropColumn("merchant");
            $table->dropColumn("merchant_id");
        });
    }
};
