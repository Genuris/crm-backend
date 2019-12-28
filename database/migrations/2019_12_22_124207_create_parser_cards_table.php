<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParserCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parser_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street')->nullable();
            $table->string('area')->nullable();
            $table->string('number_of_floors')->nullable();
            $table->string('floors_house')->nullable();
            $table->string('number_rooms')->nullable();
            $table->string('price')->nullable();
            $table->string('total_area')->nullable();
            $table->string('site_url')->nullable();
            $table->longText('description')->nullable();
            $table->longText('comment')->nullable();
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
        Schema::dropIfExists('parser_cards');
    }
}
