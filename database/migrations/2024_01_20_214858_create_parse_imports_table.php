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
        Schema::create('parse_imports', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('file')->nullable();
            $table->string('time')->nullable();
            $table->string('totalCount')->nullable();
            $table->string('job_id');
            $table->string('status');
            $table->text('error');
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
        Schema::dropIfExists('parse_imports');
    }
};
