<?php

use App\UserAffiliateTracking;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublisherColumnToUserAffiliateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->smallInteger('publisher')->unsigned()->nullable()->default(null);
        });

        $xpartnersAffiliateTrackingRows = \App\UserAffiliateTracking::where('affiliate', 'xpartners')->get();

        /** @var \App\UserAffiliateTracking $affiliateTrackingRow */
        foreach ($xpartnersAffiliateTrackingRows as $affiliateTrackingRow) {
            if (in_array($affiliateTrackingRow->getMediaId(), array_keys(UserAffiliateTracking::publisherPerMediaId()))) {
                $publisher = UserAffiliateTracking::publisherPerMediaId()[$affiliateTrackingRow->getMediaId()];
            } else {
                $publisher = null;
                \Log::debug('Media ID ' . $affiliateTrackingRow->getMediaId() . ' does not have a publisher set');
            }

            $affiliateTrackingRow->setPublisher($publisher);
            $affiliateTrackingRow->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_affiliate_tracking', function (Blueprint $table) {
            $table->dropColumn('publisher');
        });
    }
}
