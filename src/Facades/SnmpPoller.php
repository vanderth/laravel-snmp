<?php

namespace Vanderth\Snmp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Vanderth\Snmp\SnmpPoller setSnmpSession(\SNMP $session)
 * @method static \Vanderth\Snmp\SnmpPoller newSession(int $version, string $host, string $community)
 * @method static \Vanderth\Snmp\SnmpPoller addPoller(string $poller)
 * @method static \Vanderth\Snmp\SnmpPoller addPollers(array $pollers)
 * @method static array getPollers()
 * @method static array run()
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