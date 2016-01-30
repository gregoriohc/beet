<?php

namespace Gregoriohc\Beet\Routing;

use App\Models\Object;
use View;

class AdminController extends Controller
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->module = strtolower(substr(class_basename(self::class), 0, -10));

        View::share('appObjects', Object::all());

        parent::__construct();
    }
}