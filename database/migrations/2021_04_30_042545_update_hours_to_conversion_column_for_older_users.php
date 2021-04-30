<?php

use App\UserMeta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHoursToConversionColumnForOlderUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $olderConversions = \App\User::with(['payments' => function($query) {
                $query->where('is_conversion', true);
            }])
            ->whereHas('payments', function ($query) {
                $query->where('is_conversion', true);
            })
            ->get();

        /** @var \App\User $conversion */
        foreach ($olderConversions as $conversion) {
            /** @var \App\UserMeta $meta */
            $meta = $conversion->meta;

            if (null === $meta->getHoursToConversion()) {
                /** @var \Carbon\Carbon $conversionCreatedAt */
                $conversionCreatedAt = $conversion->payments[0]->created_at;
                $hoursToConversion = $conversionCreatedAt->diffInHours($conversion->getCreatedAt());

                /** @var UserMeta $userMeta */
                $userMeta = $conversion->meta;

                $userMeta->setHoursToConversion($hoursToConversion);
                $conversion->meta()->save($userMeta);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
