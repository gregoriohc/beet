<?php

namespace Gregoriohc\Beet\Routing;

use Gregoriohc\Beet\Http\Controllers\Controller as BaseController;

class WebController extends BaseController
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->module = 'web';

        parent::__construct();
    }
}