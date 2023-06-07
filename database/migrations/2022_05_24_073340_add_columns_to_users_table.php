<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->string("phone")->unique();
            $table->enum("active", ["Y", "N"])->default("Y");
            $table->enum("role", ["user", "merchant", "admin", "logist"])->default("user");
            $table->string("bin")->nullable();
            $table->string("shot_name")->nullable();
            $table->string("full_name")->nullable();
            $table->string("reg_form")->nullable();
            $table->string("address")->nullable();
            $table->string("iban")->nullable();
            $table->string("inn")->nullable();
            $table->string("shop_name")->nullable();
            $table->text("body")->nullable();
            $table->integer("status")->default(0)->index();
            $table->timestamp("last_seen")->nullable();
        });

        DB::table("users")->insert([
            "name" => "admin",
            "email" => "admin",
            "phone" => "+1 (111) 111-1111",
            "password" => Hash::make("admin"),
            "role" => "admin",
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("phone");
            $table->dropColumn("active");
            $table->dropColumn("role");
            $table->dropColumn("bin");
            $table->dropColumn("shot_name");
            $table->dropColumn("full_name");
            $table->dropColumn("reg_form");
            $table->dropColumn("address");
            $table->dropColumn("iban");
            $table->dropColumn("inn");
            $table->dropColumn("shop_name");
            $table->dropColumn("body");
            $table->dropColumn("status");
            $table->dropColumn("last_seen");
        });
    }
};
