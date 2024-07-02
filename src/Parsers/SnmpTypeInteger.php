<?php

namespace Vanderth\Snmp\Parsers;

use Vanderth\Snmp\Interfaces\SnmpParserInterface;

final class SnmpTypeInteger implements SnmpParserInterface
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
     * Returns the parsed data.
     *
     * @return mixed
     */
    public function parse() : mixed
    {
        if (str_contains($this->data, ':')) {
            return (int) explode(': ', $this->data)[1];
        }

        return (int) $this->data;
    }
}