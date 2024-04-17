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
        Schema::create('kp_post_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('kp_locations_id');
            $table->string('name');
            $table->string('title');
            $table->string('postcode')->nullable();
            $table->string('new_postcode')->nullable();
            $table->string('type')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('k_p_post_codes');
    }
};
