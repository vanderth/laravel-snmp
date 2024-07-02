<?php

namespace Vanderth\Snmp\Tests;

use PHPUnit\Framework\TestCase;
use Vanderth\Snmp\Pollers\IfSysPoller;
use Vanderth\Snmp\Pollers\IfTablePoller;
use Vanderth\Snmp\Pollers\InterfacePoller;
use Vanderth\Snmp\SnmpPoller;
use SNMP;

final class SnmpPollerTest extends TestCase
{
    public function testCanCreateAnSnmpInstance() : void
    {
        $snmp = snmp_session(SnmpPoller::V2c, 'localhost', 'public');

        $this->assertInstanceOf(SNMP::class, $snmp);
    }

    public function testIfSysPoller()
    {
        $snmp = new SNMP(SnmpPoller::V2c, 'localhost', 'public');

        $poller = new SnmpPoller();
        $expectArray = $poller->setSnmpSession($snmp)->addPoller(IfSysPoller::class)->run();

        $this->assertIsArray($expectArray);

        return $expectArray;
    }

    public function testCustomPoller()
    {
        $session = new SNMP(SnmpPoller::V2c, 'localhost', 'public');

        $poller = new SnmpPoller();
        $result = $poller->setSnmpSession($session)->addPoller(IfSysPoller::class)->run();

        $this->assertIsArray($result);

        var_dump(json_encode($result, JSON_PRETTY_PRINT));
    }

    public function testCanCreateADiscoveryPollerInstance() : IfSysPoller
    {
        $poller = new IfSysPoller();

        $this->assertInstanceOf(IfSysPoller::class, $poller);

        return $poller;
    }

    public function testIfSysPollerReturnAnArray() : void
    {
        $poller = new IfSysPoller();
        $this->assertIsArray($poller->getOids());
    }

    public function testIfSysPollerReturnInfo() : void
    {
        $poller = new IfSysPoller();
        $this->assertIsObject($poller->info());
    }

    public function testIfSysPollerArrayHasKey() : void
    {
        $poller = new IfSysPoller();
        $this->assertArrayHasKey('sysObjectID', $poller->getOids());
    }
}