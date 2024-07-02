<?php

namespace Vanderth\Snmp\Pollers;

use Vanderth\Snmp\Interfaces\SnmpPollerInterface;

class SnmpBasePoller implements SnmpPollerInterface
{
    /**
     * @var bool
     */
    protected bool $is_table = false;

    /**
     * @var array
     */
    protected array $oids = [];

    /**
     * @var string
     */
    protected string $table = '';

    /**
     * @return array
     */
    public function getOids() : array
    {
        return $this->oids;
    }

    /**
     * @return string
     */
    public function getTable() : string|null
    {
        return $this->isTable() ? $this->table : null;
    }

    /**
     * @return bool
     */
    public function isTable() : bool
    {
        return ! empty($this->table) || isset($this->table);
    }

    /**
     * @return object
     */
    public function info() : object
    {
        return (object) [
            'is_table' => $this->is_table,
            'oids' => $this->oids,
            'table' => $this->table,
        ];
    }
}