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
        Schema::create('order_copies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('status');
            $table->string('postcode');
            $table->string('article');
            $table->string('merchant');
            $table->integer('merchant_id');
            $table->integer('postcode_id');
            $table->integer('barcode');
            $table->string('pdf')->nullable();
            $table->string('res')->nullable();
            $table->string('real_weight')->nullable();
            $table->string('surname')->nullable();
            $table->string('name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('country_name')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('region_name')->nullable();
            $table->integer('area_id')->nullable();
            $table->string('area_name')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('city_name')->nullable();

            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('room')->nullable();
            $table->string('time')->nullable();
            $table->string('comment')->nullable();
            $table->double('price')->nullable();
            $table->string('pickup')->default('N');


            $table->double('delivery_price')->nullable();
            $table->double('tr_delivery_price')->nullable();

            $table->string('payment')->nullable();

            $table->double('delivery_kz_weighing')->nullable();
            $table->double('delivery_tr_weighing')->nullable();
            $table->text('hash')->nullable();
            $table->double('sale')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_copies');
    }
};
