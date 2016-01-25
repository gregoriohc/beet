<?php

namespace Gregoriohc\Beet;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gregoriohc\Html\FormBuilder
 */
class FormFacade extends Facade
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
