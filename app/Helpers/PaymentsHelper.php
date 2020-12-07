<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 * Class PaymentsHelper
 * @package App\Helpers
 */
class PaymentsHelper
{
    public static $methods = [
        1 => 'ideal',
        2 => 'paysafe',
        3 => 'bancontact'
    ];

    public static $statuses = [
        1 => 'started',
        2 => 'processing',
        3 => 'completed',
        4 => 'cancelled',
        5 => 'error'
    ];

    public static function banksSelectOptions()
    {
        try {
            $options = readfile("https://transaction.digiwallet.nl/ideal/getissuers?ver=4&format=html");
            \Cache::put('banksSelectOptions', $options);
        } catch (\Exception $exception) {
            $options = \Cache::get('banksSelectOptions');
        }

        if (!Str::contains($options, '<option')) {
            $options = '<option selected="selected" value="">Kies uw bank...</option>
                <option value="ABNANL2A">ABN AMRO</option>
                <option value="ASNBNL21">ASN Bank</option>
                <option value="BUNQNL2A">bunq</option>
                <option value="HANDNL2A">Handelsbanken</option>
                <option value="INGBNL2A">ING</option>
                <option value="KNABNL2H">Knab</option>
                <option value="MOYONL21">Moneyou</option>
                <option value="RABONL2U">Rabobank</option>
                <option value="RBRBNL21">RegioBank</option>
                <option value="SNSBNL2A">SNS Bank</option>
                <option value="TRIONL2U">Triodos Bank</option>
                <option value="FVLBNL22">van Lanschot</option>';
        }

        return $options;
    }
}
