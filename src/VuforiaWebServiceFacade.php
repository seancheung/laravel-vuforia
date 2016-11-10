<?php

namespace Panaroma\Vuforia\Facades;

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
        return \Panaroma\Vuforia\VuforiaWebService::class;
    }
}
