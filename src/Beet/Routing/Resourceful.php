<?php

namespace Gregoriohc\Beet\Routing;

use View;

trait Resourceful
{
    use Serviceable;

    /**
     * @var string $module
     */
    protected $module = 'module';

    /**
     * @var string $resource
     */
    protected $resource = 'resource';

    /**
     * @param string $moduleControllerClass
     */
    public function bootResourceful($moduleControllerClass)
    {
        $this->bootServiceable();

        $this->module = strtolower(substr(class_basename($moduleControllerClass), 0, -10));
        $this->resource = strtolower(substr(class_basename(get_class($this)), 0, -10));

        View::share('module', $this->module);
        View::share('resource', $this->resource);
    }

    /**
     * Returns an action view
     *
     * @param string $action
     * @param array $data
     * @return View
     */
    public function view($action, $data = []) {
        $view = $this->module . '.' . snake_case($this->resource) . '.' . $action;
        if (!View::exists($view)) $view = 'beet::' . $this->module . '.base.' . $action;

        return view($view, $data);
    }

    /**
     * Returns a route name from a given action name
     *
     * @param string $action
     * @return View
     */
    public function routeName($action) {
        return $this->module . '.' . snake_case($this->resource) . '.' . $action;
    }
}
