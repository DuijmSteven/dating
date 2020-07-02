<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerificationStatusColumnToUserMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->smallInteger('email_verification_status')
                ->unsigned()
                ->nullable()
                ->default(null);
        });

        $peasants = \App\User::with(['meta', 'roles'])
        ->whereHas('roles', function ($query) {
            $query->where('id', \App\User::TYPE_PEASANT);
        })
        ->get();

        /** @var \App\User $peasant */
        foreach ($peasants as $peasant) {
            $peasant->meta->setEmailVerificationStatus(\App\UserMeta::EMAIL_VERIFICATION_STATUS_PENDING);
            $peasant->meta->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropColumn('email_verification_status');
        });
    }
}
