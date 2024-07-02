<?php

namespace Vanderth\Snmp\Pollers;

final class IfSysPoller extends SnmpBasePoller
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
     * @var array
     */
    protected array $oids = [
        'sysDescr' => '.1.3.6.1.2.1.1.1.0',
        'sysObjectID' => '.1.3.6.1.2.1.1.2.0',
        'sysUpTime' => '.1.3.6.1.2.1.1.3.0',
        'sysContact' => '.1.3.6.1.2.1.1.4.0',
        'sysName' => '.1.3.6.1.2.1.1.5.0',
        'sysLocation' => '.1.3.6.1.2.1.1.6.0',
        'sysServices' => '.1.3.6.1.2.1.1.7.0',
    ];
}