<?php

namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

/**
 * Class FormattingHelper
 * @package App\Facades\Helpers
 */
class FormattingHelper extends Facade
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
        return 'formatting_helper';
    }
}
