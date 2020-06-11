<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserAffiliateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->smallInteger('lead_eligibility')->unsigned()->default(
                \App\UserAffiliateTracking::LEAD_ELIGIBILITY_PENDING
            );
            $table->smallInteger('lead_status')->unsigned()->default(
                \App\UserAffiliateTracking::LEAD_STATUS_UNVALIDATED
            );
            $table->string('country_code', 3)->nullable()->default(null);
        });

        DB::table('user_affiliate_tracking')
            ->update([
                'lead_eligibility' => \App\UserAffiliateTracking::LEAD_ELIGIBILITY_INELIGIBLE,
                'lead_status' => \App\UserAffiliateTracking::LEAD_STATUS_VALIDATED,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->dropColumn('lead_eligibility');
            $table->dropColumn('lead_status');
            $table->dropColumn('country_code');
        });
    }
}
