<?php

namespace App\Facades\Helpers\ApplicationConstants;

use Illuminate\Support\Facades\Facade;

/**
 * Class MetaConstants
 * @package App\Facades\Helpers\ApplicationConstants
 */
class MetaConstants extends Facade
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
        return 'meta_constants';
    }
}
