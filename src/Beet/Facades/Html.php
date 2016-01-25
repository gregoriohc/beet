<?php

namespace Gregoriohc\Beet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gregoriohc\Html\HtmlBuilder
 */
class Html extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'html';
    }
}
