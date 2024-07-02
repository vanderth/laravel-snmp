<?php

namespace Vanderth\Snmp\Parsers;

use Vanderth\Snmp\Interfaces\SnmpParserInterface;

final class SnmpTypeOctetString implements SnmpParserInterface
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
            'DISPLAYSTRING: ',
            'Hex-STRING: ',
            'STRING: ',
            '"',
        ];

        $this->data = str_replace($replace, '', $data);
    }

    /**
     * @return mixed
     */
    public function parse() : mixed
    {
        if ($this->is_binary($this->data)) {
            $data = implode(':', unpack('H*', $this->data));

            if (strlen($data) == 12) {
                $mac = implode(':', str_split($data, 2));

                if (filter_var($mac, FILTER_VALIDATE_MAC)) {
                    return strtoupper(implode(':', str_split($data, 2)));
                }
            }

            return $data;
        }
        if (trim($this->data) === "") {
            return null;
        }

        return (string) trim($this->data);
    }

    /**
     * Check if value is a binary string. (b"").
     *
     * @param string $string
     *
     * @return bool
     */
    public function is_binary(string $string) : bool
    {
        if (! ctype_print($string)) {
            return true;
        }

        return false;
    }
}