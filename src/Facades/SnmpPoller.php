<?php

namespace Vanderth\Snmp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Vanderth\Snmp\SnmpPoller setSnmpSession(\SNMP $session)
 * @method \Vanderth\Snmp\SnmpPoller addPoller(string $poller)
 * @method \Vanderth\Snmp\SnmpPoller addPollers(array $pollers)
 * @method array getPollers()
 * @method array run()
 * 
 * @see \Vanderth\Snmp\SnmpPoller
 */
class SnmpPoller extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-snmp';
    }
}