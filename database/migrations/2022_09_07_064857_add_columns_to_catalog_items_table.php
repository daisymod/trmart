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
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->integer("fleece_lining")->index()->nullable();
            $table->integer("pattern")->index()->nullable();
            $table->integer("fabric_type")->index()->nullable();
            $table->integer("silhouette_type")->index()->nullable();
            $table->integer("sleeve_type")->index()->nullable();
            $table->integer("waist")->index()->nullable();
            $table->integer("style")->index()->nullable();
            $table->integer("season")->index()->nullable();
            $table->integer("hood")->index()->nullable();
            $table->integer("clasp")->index()->nullable();
            $table->integer("sleeve_length")->index()->nullable();
            $table->integer("length")->index()->nullable();
            $table->integer("details")->index()->nullable();
            $table->integer("neckline_collar")->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->dropColumn("fleece_lining");
            $table->dropColumn("pattern");
            $table->dropColumn("fabric_type");
            $table->dropColumn("silhouette_type");
            $table->dropColumn("sleeve_type");
            $table->dropColumn("waist");
            $table->dropColumn("style");
            $table->dropColumn("season");
            $table->dropColumn("hood");
            $table->dropColumn("clasp");
            $table->dropColumn("sleeve_length");
            $table->dropColumn("length");
            $table->dropColumn("details");
            $table->dropColumn("neckline_collar");
        });
    }
};
