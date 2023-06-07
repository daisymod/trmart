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
        Schema::create('item_compounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->text('name_ru')->nullable();
            $table->text('name_kz')->nullable();
            $table->text('name_tr')->nullable();
            $table->double('percent');
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
        Schema::dropIfExists('item_compounds');
    }
};
