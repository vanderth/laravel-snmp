<?php

namespace Vanderth\Snmp\Parsers;

use Vanderth\Snmp\Interfaces\SnmpParserInterface;

final class SnmpTypeTimeticks implements SnmpParserInterface
{
    /**
     * Data to be parsed.
     *
     * @var mixed
     */
    protected $data;

    /**
     * SnmpTypeTimetics constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        if (str($data)->contains('Timeticks: ')) {
            $replace = [
                'Timeticks: ',
            ];
            $this->data = trim(str($data)->replace($replace, '', true));
        }
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function parse() : mixed
    {
        if (@preg_match("/(\d+):(\d+):(\d+):(\d+).(\d+)/", $this->data, $matches)) {
            sscanf($this->data, "%d:%d:%d:%d.%d", $day, $hour, $minute, $sec, $ticks);
            return "$day days, $hour:$minute:$sec";
        }
        return $this->data;
    }
}