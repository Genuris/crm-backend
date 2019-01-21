<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //{card, contact, dateTime, type, description, remind for, responsible, creator}
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('cards_contacts_id')->unsigned()->nullable();
            $table->integer('card_id')->unsigned()->nullable();
            $table->integer('creator_id')->nullable();

            $table->timestamp('date_time')->nullable();
            $table->timestamp('remind')->nullable();

            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->longText('responsibles')->nullable();

            $table->timestamps();

            /*$table->foreign('cards_contacts_id')->references('id')->on('cards_contacts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('cards')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
