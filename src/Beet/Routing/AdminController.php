<?php

namespace Gregoriohc\Beet\Routing;

use Gregoriohc\Beet\Http\Controllers\Controller as BaseController;

class AdminController extends BaseController
{
    /**
     * Create a new web controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $reflect = new \ReflectionClass(self::class);
        $this->module = strtolower(substr($reflect->getShortName(), 0, -10));

        $reflect = new \ReflectionClass($this);
        $this->resource = strtolower(substr($reflect->getShortName(), 0, -10));

        $model = 'App\\Models\\' . substr($reflect->getShortName(), 0, -10);
        if (class_exists($model)) {
            $this->model = $model;
        }

        parent::__construct();
    }
}