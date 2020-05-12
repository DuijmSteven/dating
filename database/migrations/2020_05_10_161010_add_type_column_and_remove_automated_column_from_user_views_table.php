<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnAndRemoveAutomatedColumnFromUserViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_views', function (Blueprint $table) {
            $table->smallInteger('type')->nullable()->default(null);
            $table->dropColumn('automated');
        });

        \App\UserView::whereHas('viewer.roles', function ($query) {
            $query->where('id', \App\User::TYPE_PEASANT);
        })
        ->update([
            'type' => \App\UserView::TYPE_PEASANT
        ]);

        \App\UserView::whereHas('viewer.roles', function ($query) {
            $query->where('id', \App\User::TYPE_BOT);
        })
            ->update([
                'type' => \App\UserView::TYPE_SCHEDULED
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_views', function (Blueprint $table) {
            $table->boolean('automated')->default(false);
            $table->dropColumn('type');
        });
    }
}
