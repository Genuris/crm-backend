<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsContactsPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_contacts_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cards_contacts_id')->unsigned();
            $table->string('phone')->unique();
            $table->timestamps();
            $table->foreign('cards_contacts_id')->references('id')->on('cards_contacts')
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
        Schema::dropIfExists('cards_contacts_phones');
    }
}
