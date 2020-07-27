<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexForColumnOfConversationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->index([
                'paid',
            ]);

            $table->index([
                'created_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropIndex([
                'paid',
            ]);

            $table->dropIndex([
                'created_at',
            ]);
        });
    }
}
