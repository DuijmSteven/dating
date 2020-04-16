<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToConversationsTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->index([
                'user_a_id',
            ]);

            $table->index([
                'user_b_id',
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
        Schema::table('conversation', function (Blueprint $table) {
            $table->dropIndex([
                'user_a_id',
            ]);

            $table->dropIndex([
                'user_b_id',
            ]);
        });
    }
}
