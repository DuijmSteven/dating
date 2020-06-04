<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiTokenColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 80)
                ->unique()
                ->nullable()
                ->default(null);
        });

        $users = \App\User::whereHas('roles', function ($query) {
            $query->where('id', '!=', \App\User::TYPE_BOT);
        })->get();

        /** @var \App\User $user */
        foreach ($users as $user) {
            $user->setApiToken(Str::random(60));
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
}
