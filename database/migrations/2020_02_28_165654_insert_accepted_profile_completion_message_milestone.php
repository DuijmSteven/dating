<?php

use App\Milestone;
use Illuminate\Database\Migrations\Migration;

class InsertAcceptedProfileCompletionMessageMilestone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Milestone::insert([
            [
                'name' => 'profile_completion_message_accepted',
                'description' => 'This milestone is awarded when the user accepts the message that prompts him to fill his profile details',
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
        Milestone::where('name', 'profile_completion_message_accepted')->delete();
    }
}
