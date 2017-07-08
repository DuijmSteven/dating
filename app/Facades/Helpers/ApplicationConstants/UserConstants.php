<?php

namespace App\Facades\Helpers\ApplicationConstants;

use Illuminate\Support\Facades\Facade;

/**
 * Class UserConstants
 * @package App\Facades\Helpers\ApplicationConstants
 */
class UserConstants extends Facade
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
        return 'user_constants';
    }
}
