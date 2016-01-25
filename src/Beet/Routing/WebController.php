<?php

namespace Gregoriohc\Beet\Routing;

class WebController extends Controller
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->module = strtolower(substr(class_basename(self::class), 0, -10));

        parent::__construct();
    }
}