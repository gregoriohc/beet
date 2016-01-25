<?php

namespace Gregoriohc\Beet\Routing;

class AdminController extends Controller
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->module = strtolower(substr(class_basename(self::class), 0, -10));

        parent::__construct();
    }
}