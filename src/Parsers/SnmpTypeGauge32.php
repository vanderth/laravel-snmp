<?php

namespace Vanderth\Snmp\Parsers;

use Vanderth\Snmp\Interfaces\SnmpParserInterface;

final class SnmpTypeGauge32 implements SnmpParserInterface
{
    /**
     * Data to be parsed.
     *
     * @var mixed
     */
    protected $data;

    /**
     * SnmpTypeCounter32 constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function parse() : mixed
    {
        return $this->data;
        //        return (int) explode(': ', $this->data)[1];
    }
}