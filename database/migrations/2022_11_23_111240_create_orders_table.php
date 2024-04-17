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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("user_id")->index()->default(0);
            $table->integer("status")->index()->default(0);
            $table->string("surname")->nullable();
            $table->string("name")->nullable();
            $table->string("middle_name")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("country_name")->nullable();
            $table->integer("country_id")->index()->default(0);
            $table->string("city_name")->nullable();
            $table->integer("city_id")->index()->default(0);
            $table->string("street")->nullable();
            $table->string("house_number")->nullable();
            $table->string("room")->nullable();
            $table->string("time")->nullable();
            $table->text("comment")->nullable();
            $table->float("price")->nullable();
            $table->enum("pickup", ["Y", "N"])->nullable();
            $table->float("delivery_price")->nullable();
            $table->date("delivery_dt_start")->nullable();
            $table->date("delivery_dt_end")->nullable();
            $table->string("payment")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
