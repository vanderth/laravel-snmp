<?php

namespace Vanderth\Snmp\Parsers;

use stdClass;
use Vanderth\Snmp\Interfaces\SnmpParserInterface;

class SnmpParser implements SnmpParserInterface
{
    /**
     * @var mixed
     */
    protected $data;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    /**
     * @var array
     */
    protected array $pattern = [
        'IpAddress: ',
        'DISPLAYSTRING: ',
        'Hex-STRING: ',
        'STRING: ',
        'OID: ',
        'Timeticks: ',
        'INTEGER: '
    ];


    private function parseType(int $type, mixed $value) : mixed
    {
        return match ($type) {
            SNMP_BIT_STR => 'bit_str: ' . $value,
            65, SNMP_COUNTER => (new SnmpTypeCounter32($value))->parse(),
            SNMP_COUNTER64 => (new SnmpTypeCounter64($value))->parse(),
            SNMP_INTEGER => (new SnmpTypeInteger($value))->parse(),
            SNMP_IPADDRESS => (new SnmpTypeIPAddress($value))->parse(),
            SNMP_NULL => 'null' . $value,
            SNMP_OBJECT_ID => (new SnmpTypeOID($value))->parse(),
            SNMP_OCTET_STR => (new SnmpTypeOctetString($value))->parse(),
            SNMP_OPAQUE => 'opaque: ' . $value,
            SNMP_TIMETICKS => (new SnmpTypeTimeticks($value))->parse(),
            SNMP_UNSIGNED => 'unsinged: ' . $value,
            SNMP_UINTEGER => 'uinteger: ' . $value,
            default => [
                'type' => $type,
                'value' => $value,
            ],
        };
    }

    private function parseString(string $value) : string
    {
        $value = str_replace($this->pattern, '', $value);

        // Catch string from timeticks
        if (@preg_match("/(\d+):(\d+):(\d+):(\d+).(\d+)/", $value, $matches)) {
            sscanf($value, "%d:%d:%d:%d.%d", $day, $hour, $minute, $sec, $ticks);
            return "$day days, $hour:$minute:$sec";
        }

        return $value;
    }

    /**
     * @return mixed
     */
    public function parse() : mixed
    {
        if ($this->data instanceof stdClass || is_object($this->data)) {
            return $this->parseType($this->data->type, $this->data->value);
        }

        if (is_string($this->data)) {
            return $this->parseString($this->data);
        }

        return $this->data;
    }
}