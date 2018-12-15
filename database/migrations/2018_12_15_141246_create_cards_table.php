<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('agency_id')->unsigned();
            $table->integer('office_id')->unsigned();

            $table->string('type')->nullable();
            $table->string('sale_type')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('street')->nullable();
            $table->string('building')->nullable();
            $table->string('apartment')->nullable();
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('landmark')->nullable();
            $table->string('owner_or_realtor')->nullable();
            $table->integer('year_built')->nullable();
            $table->string('floors_house')->nullable();
            $table->integer('number_rooms')->nullable();
            $table->string('type_building')->nullable();
            $table->string('roof')->nullable();
            $table->string('total_area')->nullable();
            $table->string('living_area')->nullable();
            $table->string('kitchen_area')->nullable();
            $table->string('ceiling_height')->nullable();
            $table->string('condition')->nullable();
            $table->string('heating')->nullable();
            $table->string('electricity')->nullable();
            $table->string('water_pipes')->nullable();
            $table->string('bathroom')->nullable();
            $table->string('sewage')->nullable();
            $table->string('internet')->nullable();
            $table->string('gas')->nullable();
            $table->string('security')->nullable();
            $table->string('land_area')->nullable();
            $table->string('how_plot_fenced')->nullable();
            $table->string('entrance_door')->nullable();
            $table->string('furniture')->nullable();
            $table->string('window')->nullable();
            $table->string('view_from_windows')->nullable();
            $table->boolean('garbage_chute');
            $table->string('layout')->nullable();
            $table->string('subtypes')->nullable();
            $table->string('description')->nullable();
            $table->string('comment')->nullable();


            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('agency_id')->references('id')->on('agencies')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('office_id')->references('id')->on('offices')
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
        Schema::dropIfExists('cards');
    }
}
