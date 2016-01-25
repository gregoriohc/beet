<?php

namespace Gregoriohc\Beet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gregoriohc\Html\FormBuilder
 */
class Form extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
