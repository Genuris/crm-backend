<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParserCardsPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parser_cards_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parser_cards_id')->unsigned();
            $table->string('phone')->unique();
            $table->timestamps();
            $table->foreign('parser_cards_id')->references('id')->on('parser_cards')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parser_cards_phones');
    }
}
