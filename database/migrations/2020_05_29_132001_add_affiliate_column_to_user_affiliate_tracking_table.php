<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAffiliateColumnToUserAffiliateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->string('affiliate');
        });

        \App\UserAffiliateTracking::query()->update(['affiliate' => \App\UserAffiliateTracking::AFFILIATE_XPARTNERS]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->dropColumn('affiliate');
        });
    }
}
