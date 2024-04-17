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
            $table->string("tckn")->nullable();
            $table->string("vkn")->nullable();
            $table->string("district")->nullable();
            $table->string("address_invoice")->nullable();
            $table->string("address_return")->nullable();
            $table->integer("type_invoice")->nullable();
            $table->integer("sale_category")->nullable();
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
            $table->dropColumn("tckn");
            $table->dropColumn("vkn");
            $table->dropColumn("district");
            $table->dropColumn("address_invoice");
            $table->dropColumn("address_return");
            $table->dropColumn("type_invoice");
            $table->dropColumn("sale_category");
        });
    }
};
