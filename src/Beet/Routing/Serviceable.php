<?php

namespace Gregoriohc\Beet\Routing;

use View;
use Gregoriohc\Beet\Support\Service;

trait Serviceable
{
    /**
     * @var Service $service
     */
    protected $service = null;

    /**
     * Initialize serviceable
     */
    public function bootServiceable()
    {
        $serviceName = substr(class_basename(get_class($this)), 0, -10) . 'Service';
        $serviceClass = 'App\\Services\\' . $serviceName;

        if (class_exists($serviceClass)) {
            $this->service = app()->make($serviceClass);
        }

        View::share('service', $this->service);
    }
}
