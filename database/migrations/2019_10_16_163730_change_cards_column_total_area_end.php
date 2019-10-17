<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCardsColumnTotalAreaEnd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function(Blueprint $table) {
            $table->string('total_area_end')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->float('total_area_end')->nullable()->change();
        });
    }
}
