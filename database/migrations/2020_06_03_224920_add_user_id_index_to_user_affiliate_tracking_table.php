<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdIndexToUserAffiliateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->index([
                'user_id',
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
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->dropIndex([
                'user_id',
            ]);
        });
    }
}