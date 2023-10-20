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
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('catalog')->nullable();
            $table->string('brand')->nullable();
            $table->string('article')->nullable();
            $table->string('barcode')->nullable();
            $table->string('price_from')->nullable();
            $table->string('price_to')->nullable();
            $table->string('user')->nullable();
            $table->string('status')->nullable();
            $table->string('sort_by')->nullable();
            $table->string('limit')->nullable();
            $table->string('page')->nullable();
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
        Schema::dropIfExists('admin_settings');
    }
};
