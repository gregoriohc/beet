<?php

namespace Gregoriohc\Beet\Http\Controllers;

use Gregoriohc\Beet\Http\Controllers\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->module = 'api';

        parent::__construct();
    }
}