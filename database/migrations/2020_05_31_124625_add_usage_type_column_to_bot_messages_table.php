<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsageTypeColumnToBotMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->smallInteger('usage_type')->unsigned()->nullable();
        });

        DB::table('bot_messages')->update(['usage_type' => \App\BotMessage::USAGE_TYPE_NORMAL_CHAT]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->dropColumn('usage_type');
        });
    }
}
