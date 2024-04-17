<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('delivery_prices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->float("kg_price")->nullable();
            $table->float("gr_price")->nullable();
            $table->timestamps();
        });
        DB::table('delivery_prices')->insert(
            array(
                [
                    'name' => 'Турция',
                    'kg_price' => floatval(500),
                    'gr_price' => floatval(0.5),
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_prices');
    }
};
