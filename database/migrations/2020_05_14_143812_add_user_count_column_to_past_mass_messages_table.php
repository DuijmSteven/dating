<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserCountColumnToPastMassMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('past_mass_messages', function (Blueprint $table) {
            $table->bigInteger('user_count');
        });

        \App\PastMassMessage::query()->update(['user_count' => 643]);

        \App\PastMassMessage::insert([
            [
                'body' => 'Hey de zon! Weet jij nog een leuk plekje, parkje, bosje, strandje?',
                'user_count' => 165,
                'created_at' => '2020-05-13 15:25:30',
                'updated_at' => '2020-05-13 15:25:30'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('past_mass_messages', function (Blueprint $table) {
            $table->dropColumn('user_count');
        });
    }
}
