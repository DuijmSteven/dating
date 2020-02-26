<?php

use App\EmailType;
use Illuminate\Database\Migrations\Migration;

class InsertProfileViewedEmailTypeToEmailTypesTable extends Migration
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
                'name' => 'profile_viewed',
                'description' => 'This email is sent when a user views another user\'s profile',
            ],
        ]);

        $peasants = \App\User::whereHas('roles', function ($query) {
           $query->where('id', \App\User::TYPE_PEASANT);
        })->get();

        /** @var \App\User $peasant */
        foreach ($peasants as $peasant) {
            $emailTypeUserInstance = new \App\EmailTypeUser([
                'email_type_id' => EmailType::PROFILE_VIEWED,
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
        EmailType::where('name', 'profile_viewed')->delete();
    }
}
