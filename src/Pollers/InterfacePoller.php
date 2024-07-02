<?php

namespace Vanderth\Snmp\Pollers;

final class InterfacePoller extends SnmpBasePoller
{
    /**
     * @var bool
     */
    protected bool $is_table = false;

    /**
     * @var string
     */
    protected string $table = '';

    /**
     * Get Interface poller
     * 
     * @var array
     */
    protected array $oids = [
        'ifIndex' => '.1.3.6.1.2.1.2.2.1.1',
        'ifDescr' => '.1.3.6.1.2.1.2.2.1.2',
        'ifAlias' => '.1.3.6.1.2.1.31.1.1.1.18',
        'ifAdminStatus' => '.1.3.6.1.2.1.2.2.1.7',
        'ifOperStatus' => '.1.3.6.1.2.1.2.2.1.8',
        'ifLastChange' => '.1.3.6.1.2.1.2.2.1.9',
    ];
}