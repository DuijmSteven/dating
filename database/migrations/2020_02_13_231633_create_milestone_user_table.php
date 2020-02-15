<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestoneUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('milestone_id')->unsigned();
        });

        foreach (\App\User::all() as $user) {
            $user->milestones()->attach(\App\Milestone::ACCEPTED_WELCOME_MESSAGE);
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone_user');
    }
}
