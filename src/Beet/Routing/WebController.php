<?php

namespace Gregoriohc\Beet\Routing;

use Illuminate\Routing\Controller as Controller;

abstract class WebController extends Controller
{
    use Resourceful;

    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->bootResourceful(self::class);
    }
}