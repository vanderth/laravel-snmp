<?php

namespace Vanderth\Snmp\Interfaces;

interface SnmpParserInterface
{
    /**
     * Parse SNMP data.
     *
     * @return mixed
     */
    public function parse() : mixed;
}