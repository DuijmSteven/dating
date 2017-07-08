<?php

namespace App\Helpers;

/**
 * Class PaymentsHelper
 * @package App\Helpers
 */
class PaymentsHelper
{
    public static $methods = [
        1 => 'ideal',
        2 => 'paysafe',
        3 => 'ivr'
    ];

    public static $statuses = [
        1 => 'started',
        2 => 'processing',
        3 => 'completed',
        4 => 'cancelled',
        5 => 'error'
    ];
}
