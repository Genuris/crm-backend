<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('card_id')->unsigned();
            $table->integer('file_id')->unsigned();
            $table->timestamps();

            $table->foreign('card_id')->references('id')->on('cards')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')
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
        Schema::dropIfExists('cards_files');
    }
}
