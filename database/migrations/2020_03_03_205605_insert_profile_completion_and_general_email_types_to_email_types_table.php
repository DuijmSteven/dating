<?php

use App\EmailType;
use Illuminate\Database\Migrations\Migration;

class InsertProfileCompletionAndGeneralEmailTypesToEmailTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        EmailType::insert([
            [
                'name' => 'profile_completion',
                'description' => 'This email is sent to users with a low profile completion score or no profile image',
                'editable' => false
            ],
            [
                'name' => 'general',
                'description' => 'This type is used to check if the user wants to receive general notifications',
                'editable' => true
            ],
        ]);

        $peasants = \App\User::whereHas('roles', function ($query) {
            $query->where('id', \App\User::TYPE_PEASANT);
        })->get();

        /** @var \App\User $peasant */
        foreach ($peasants as $peasant) {
            $emailTypeUserInstance = new \App\EmailTypeUser([
                'email_type_id' => EmailType::GENERAL,
                'user_id' => $peasant->getId()
            ]);

            $emailTypeUserInstance->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        EmailType::where('name', 'profile_completion')->delete();

    }
}
