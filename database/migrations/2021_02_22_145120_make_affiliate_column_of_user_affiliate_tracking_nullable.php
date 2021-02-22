<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAffiliateColumnOfUserAffiliateTrackingNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->string('affiliate')->nullable()->change();
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
            $table->string('affiliate')->nullable(false)->change();
        });
    }
}
