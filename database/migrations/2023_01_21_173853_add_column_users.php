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
            $table->date("birthday")->nullable();
            $table->string("telegram")->nullable();
            $table->string("whatsapp")->nullable();
            $table->integer("gender")->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("birthday");
            $table->dropColumn("telegram");
            $table->dropColumn("whatsapp");
            $table->dropColumn("gender");
        });
    }
};
