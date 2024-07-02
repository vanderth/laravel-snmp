<?php

namespace Vanderth\Snmp\Pollers;

final class IfExtendTablePoller extends SnmpBasePoller
{
    protected bool $is_table = true;

    protected string $table = 'ifXTable';

    protected array $oids = [
        'ifName' => '.1.3.6.1.2.1.31.1.1.1.1',
        'ifInMulticastPkts' => '.1.3.6.1.2.1.31.1.1.1.2',
        'ifInBroadcastPkts' => '.1.3.6.1.2.1.31.1.1.1.3',
        'ifOutMulticastPkts' => '.1.3.6.1.2.1.31.1.1.1.4',
        'ifOutBroadcastPkts' => '.1.3.6.1.2.1.31.1.1.1.5',
        'ifHCInOctets' => '.1.3.6.1.2.1.31.1.1.1.6',
        'ifHCInUcastPkts' => '.1.3.6.1.2.1.31.1.1.1.7',
        'ifHCInMulticastPkts' => '.1.3.6.1.2.1.31.1.1.1.8',
        'ifHCInBroadcastPkts' => '.1.3.6.1.2.1.31.1.1.1.9',
        'ifHCOutOctets' => '.1.3.6.1.2.1.31.1.1.1.10',
        'ifHCOutUcastPkts' => '.1.3.6.1.2.1.31.1.1.1.11',
        'ifHCOutMulticastPkts' => '.1.3.6.1.2.1.31.1.1.1.12',
        'ifHCOutBroadcastPkts' => '.1.3.6.1.2.1.31.1.1.1.13',
        'ifLinkUpDownTrapEnable' => '.1.3.6.1.2.1.31.1.1.1.14',
        'ifHighSpeed' => '.1.3.6.1.2.1.31.1.1.1.15',
        'ifPromiscuousMode' => '.1.3.6.1.2.1.31.1.1.1.16',
        'ifConnectorPresent' => '.1.3.6.1.2.1.31.1.1.1.17',
        'ifAlias' => '.1.3.6.1.2.1.31.1.1.1.18',
        'ifCounterDiscontinuityTime' => '.1.3.6.1.2.1.31.1.1.1.19',
    ];
}