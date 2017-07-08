<?php

namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

/**
 * Class StorageHelper
 * @package App\Facades\Helpers\ApplicationConstants
 */
class StorageHelper extends Facade
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
        return 'storage_helper';
    }
}

