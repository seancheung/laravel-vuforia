<?php

namespace Panoscape\Vuforia\Facades;

use Illuminate\Support\Facades\Facade;

class VuforiaWebService extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Panoscape\Vuforia\VuforiaWebService::class;
    }
}
