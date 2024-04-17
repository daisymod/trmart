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
            $table->string('postcode')->after('status')->nullable();
            $table->string('barcode')->after('postcode')->nullable();
            $table->binary('pdf')->after('barcode')->nullable();
            $table->binary('res')->after('pdf')->nullable();
            $table->string('real_weight')->after('res')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('postcode');
            $table->dropColumn('barcode');
            $table->dropColumn('pdf');
            $table->dropColumn('res');
            $table->dropColumn('real_weight');
        });
    }
};
