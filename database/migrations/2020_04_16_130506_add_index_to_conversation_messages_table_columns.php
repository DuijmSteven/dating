<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToConversationMessagesTableColumns extends Migration
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
                'conversation_id',
            ]);

            $table->index([
                'sender_id',
            ]);

            $table->index([
                'recipient_id',
            ]);

            $table->index([
                'operator_id',
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
                'conversation_id',
            ]);

            $table->dropIndex([
                'sender_id',
            ]);

            $table->dropIndex([
                'recipient_id',
            ]);

            $table->dropIndex([
                'operator_id',
            ]);
        });
    }
}
