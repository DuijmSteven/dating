<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsConversionColumnToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_conversion')->default(false);
        });

        $payments = \App\Payment
            ::where('status', \App\Payment::STATUS_COMPLETED)
            ->get();

        /** @var \App\Payment $payment */
        foreach ($payments as $payment) {
            $earlierCompletedPayments = \App\Payment
                ::where('status', \App\Payment::STATUS_COMPLETED)
                ->where('user_id', $payment->getUserId())
                ->where(
                    'created_at',
                    '<',
                    $payment->created_at->tz('UTC')->toDateTimeString()
                )
                ->where('id', '!=', $payment->id)
                ->count();

            if ($earlierCompletedPayments === 0) {
                $payment->setIsConversion(true);
                $payment->save();
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
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('is_conversion');
        });
    }
}
