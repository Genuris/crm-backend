<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardsContactsPhonesColumnAgencyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards_contacts_phones', function (Blueprint $table) {
            $table->integer('agency_id')->after('cards_contacts_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards_contacts_phones', function (Blueprint $table) {
            $table->dropColumn('agency_id');
        });
    }
}
