<?php

namespace App\Facades\Helpers\ApplicationConstants;

use Illuminate\Support\Facades\Facade;

/**
 * Class PaginationConstants
 * @package App\Facades\Helpers\ApplicationConstants
 */
class PaginationConstants extends Facade
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
        return 'pagination_constants';
    }
}
