<?php

namespace Vanderth\Snmp\Interfaces;

interface SnmpPollerInterface
{
    /**
     * @return array
     */
    public function getOids() : array;

    /**
     * @return string
     */
    public function getTable() : string|null;

    /**
     * @return object
     */
    public function info() : object;

    /**
     * @return bool     
     */
    public function isTable() : bool;
}