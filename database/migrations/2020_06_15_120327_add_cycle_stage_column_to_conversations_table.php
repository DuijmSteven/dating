<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCycleStageColumnToConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->smallInteger('cycle_stage')->unsigned()->nullable()->default(null);
            $table->index([
                'cycle_stage',
            ]);
        });

        $conversationManager = new \App\Managers\ConversationManager(
            new \App\Conversation(),
            new \App\ConversationMessage(),
            new \App\Managers\StorageManager()
        );

        $unrepliedConversations = $conversationManager->unrepliedPeasantBotConversations();
        $newConversations = $conversationManager->newPeasantBotConversations();
        $stoppedConversations = $conversationManager->stoppedPeasantBotConversations();

        foreach ($unrepliedConversations as $conversation) {
            $conversation->setCycleStage(\App\Conversation::CYCLE_STAGE_UNREPLIED);
            $conversation->save();
        }

        foreach ($newConversations as $conversation) {
            $conversation->setCycleStage(\App\Conversation::CYCLE_STAGE_NEW);
            $conversation->save();
        }

        foreach ($stoppedConversations as $conversation) {
            $conversation->setCycleStage(\App\Conversation::CYCLE_STAGE_STOPPED);
            $conversation->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex([
                'cycle_stage',
            ]);

            $table->dropColumn('cycle_stage');
        });
    }
}
