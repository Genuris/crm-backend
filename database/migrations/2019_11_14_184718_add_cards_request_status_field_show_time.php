<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardsRequestStatusFieldShowTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards_request_status', function (Blueprint $table) {
            $table->bigInteger('show_time')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards_request_status', function (Blueprint $table) {
            $table->dropColumn('show_time');
        });
    }
}
