<?php

namespace Vanderth\Snmp\Facades;

use Illuminate\Support\Facades\Facade;

class SnmpPoller extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-snmp';
    }
}