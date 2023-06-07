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
        Schema::dropIfExists('cities');
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('country_id');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->timestamps();
        });
        DB::table('cities')->insert(
            array(
                [
                    'country_id' => 1,
                    'name_ru' => 'Актау',
                    'name_en' => 'Aktau',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Актобе',
                    'name_en' => 'Aktobe',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Алматы',
                    'name_en' => 'Almaty',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Астана',
                    'name_en' => 'Astana',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Атырау',
                    'name_en' => 'Atyrau',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Жезказган',
                    'name_en' => 'Zhezkazgan',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Караганда',
                    'name_en' => 'Karaganda',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Кокшетау',
                    'name_en' => 'Kokshetau',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Костанай',
                    'name_en' => 'Kostanay',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Кызылорда',
                    'name_en' => 'Kyzylorda',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Павлодар',
                    'name_en' => 'Pavlodar',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Петропавловск',
                    'name_en' => 'Petropavlovsk',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Рудный',
                    'name_en' => 'Rudnyy',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Семей',
                    'name_en' => 'Semey',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Талдыкорган',
                    'name_en' => 'Taldykorgan',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Тараз',
                    'name_en' => 'Taraz',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Темиртау',
                    'name_en' => 'Temirtau',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Туркестан',
                    'name_en' => 'Turkestan',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Уральск',
                    'name_en' => 'Uralsk',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Усть-Каменогорск',
                    'name_en' => 'Ust-Kamenogorsk',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Шымкент',
                    'name_en' => 'Shymkent',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Экибастуз',
                    'name_en' => 'Ekibastuz',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
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
        Schema::dropIfExists('cities');
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('country_id');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->timestamps();
        });
        DB::table('cities')->insert(
            array(
                [
                    'country_id' => 1,
                    'name_ru' => 'Алматы',
                    'name_en' => 'Almaty',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'country_id' => 1,
                    'name_ru' => 'Москва1',
                    'name_en' => 'Moscow',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]
            )
        );
    }
};
