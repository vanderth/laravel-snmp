<?php

namespace Vanderth\Snmp\Parsers;

use Vanderth\Snmp\Interfaces\SnmpParserInterface;

final class SnmpTypeIPAddress implements SnmpParserInterface
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
        $replace = [
            'IpAddress: ',
            '"',
        ];

        $this->data = str_replace($replace, '', $data);
    }

    /**
     * @return string
     */
    public function parse() : string
    {
        return (string) trim($this->data);
    }
}