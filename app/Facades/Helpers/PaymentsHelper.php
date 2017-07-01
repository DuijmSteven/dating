<?php

namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

/**
 * Class PaymentsHelper
 * @package App\Facades\Helpers\ApplicationConstants
 */
class PaymentsHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'payments_helper';
    }
}
