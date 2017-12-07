<?php

namespace Abel\ApiProxy;

use Illuminate\Support\Facades\Facade as Base;

class Facade extends Base
{
    protected static function getFacadeAccessor()
    {
        return ApiProxy::class;
    }
}
