<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressAndFirstAndLastNameColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 80)
                ->nullable()
                ->default(null);

            $table->string('last_name', 80)
                ->nullable()
                ->default(null);

            $table->string('postal_code', 20)
                ->nullable()
                ->default(null);

            $table->string('street_name', 80)
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('postal_code');
            $table->dropColumn('street_name');
        });
    }
}
