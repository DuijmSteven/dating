<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoursToConversionColumnToUserMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->integer('hours_to_conversion')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->integer('hours_to_conversion');
        });
    }
}
