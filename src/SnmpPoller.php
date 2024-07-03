<?php

namespace Vanderth\Snmp;

use SNMP;
use ReflectionClass;
use stdClass;
use Vanderth\Snmp\Interfaces\SnmpPollerInterface;
use Vanderth\Snmp\Parsers\SnmpParser;
use Vanderth\Snmp\Exceptions\{SnmpPollerInvalid, SnmpSessionInvalid};
use Illuminate\Support\Facades\Config;

class SnmpPoller
{
    public const V1 = 0;
    public const V2c = 1;
    /**
     * @var array
     */
    protected array $pollers = [];

    /**
     * Instance of SNMP.
     *
     * @var SNMP
     */
    protected SNMP $session;

    /**
     * SnmpPoller constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Set the SNMP session to the host.
     *
     * @param SNMP $session
     *
     * @return $this
     */
    public function setSnmpSession(SNMP $session) : SnmpPoller
    {
        $this->session = $session;

        $this->setSnmpSessionDefaultParameters();

        return $this;
    }

    /**
     * Set new SNMP session
     * 
     * @param int $version
     * @param string $host
     * @param string $community
     * 
     * @return $this
     */
    public function newSession(int $version, string $host, string $community) : SnmpPoller
    {
        $this->session = new SNMP(
            $this->getMatchVersion($version),
            $host,
            $community
        );

        $this->setSnmpSessionDefaultParameters();

        return $this;
    }

    private function getMatchVersion(int $version) : int
    {
        return match ($version) {
            1 => SNMP::VERSION_1,
            2 => SNMP::VERSION_2c,
            3 => SNMP::VERSION_3,
            default => SNMP::VERSION_2c
        };
    }

    /**
     * Adds a SNMP poller.
     *
     * @param string $poller
     *
     * @return SnmpPoller
     */
    public function addPoller(string $poller) : SnmpPoller
    {
        $this->pollers[] = new $poller();

        return $this;
    }

    /**
     * Adds an array of SNMP Pollers.
     *
     * @param array $pollers
     *
     * @return SnmpPoller
     */
    public function addPollers(array $pollers) : SnmpPoller
    {
        foreach ($pollers as $poller) {
            $this->pollers[] = new $poller();
        }

        return $this;
    }

    /**
     * Returns an array of SNMP pollers assigned to SNMP Session.
     *
     * @return array
     */
    public function getPollers() : array
    {
        return $this->pollers;
    }

    /**
     * Fetch $snmp_walk_data and return as table.
     *
     * @param array $snmp_walk_data SNMP query results
     *
     * @return array
     */
    private function transposeTable(array $snmp_walk_data) : array
    {
        $table = [];

        foreach ($snmp_walk_data as $column => $indexes) {
            foreach ($indexes as $index => $value) {
                $table[$index][$column] = $value;
            }
        }

        return $table;
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    public function run() : array
    {
        $pollers_data = [];

        if (! $this->checkSnmpSession()) {
            throw new SnmpSessionInvalid('SNMP session not defined.');
        }

        if (! $this->checkSnmpPollers()) {
            throw new SnmpPollerInvalid('Pollers not defined.');
        }

        foreach ($this->pollers as $poller) {
            $pollers_data[$this->getPollerClassName($poller)] = $this->getPollerData($poller);
        }

        $this->closeSnmpSession();

        return $pollers_data;
    }

    /**
     * Check if SNMP Pollers are defined.
     *
     * @return bool
     */
    private function checkSnmpPollers() : bool
    {
        if (count($this->pollers) == 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if SNMP session is defined.
     *
     * @return bool
     */
    private function checkSnmpSession() : bool
    {
        if (count($this->pollers) == 0) {
            return false;
        }

        if (! isset($this->session)) {
            $this->getDefaultSessionConfig();
            return true;
        }

        return true;
    }

    private function getDefaultSessionConfig()
    {
        $session = new SNMP(
            Config::get('snmp.session.default.version'),
            Config::get('snmp.session.default.host'),
            Config::get('snmp.session.default.community')
        );
        $this->session = $session;
        $this->setSnmpSessionDefaultParameters();
    }

    /**
     * Close SNMP session.
     */
    private function closeSnmpSession() : void
    {
        $this->session->close();
    }

    /**
     * Runs the SNMP query on the host.
     *
     * @param $poller
     *
     * @return stdClass
     */
    private function getPollerData($poller) : stdClass
    {
        if ($poller->isTable()) {
            return $this->walk($poller);
        }

        return $this->get($poller);
    }

    /**
     * Set the default parameters of the SNMP session.
     */
    private function setSnmpSessionDefaultParameters() : void
    {
        $this->session->exceptions_enabled = true;
        $this->session->oid_output_format = SNMP_OID_OUTPUT_NUMERIC;
        $this->session->quick_print = true;
    }

    /**
     * Returns an array with the error data.
     *
     * @return stdClass
     */
    private function returnErrorData() : array
    {
        return [
            'code' => $this->session->getErrno(),
            'message' => $this->session->getError(),
            'result' => null,
        ];
    }

    /**
     * Get a SNMP leaf.
     *
     * @param SnmpPollerInterface $poller
     *
     * @return stdClass
     */
    private function get(SnmpPollerInterface $poller) : stdClass
    {
        $snmp_get = [];

        foreach ($poller->getOids() as $key => $oid) {
            $query = $this->session->get($oid, true);

            if (! $query) {
                $this->returnErrorData();
            }

            $parser = new SnmpParser($query);

            $snmp_get[$key] = $parser->parse();
        }

        return (object) [
            'data' => $snmp_get,
            'poller' => get_class($poller),
            'result' => $query ? 'OK' : 'Failed',
        ];
    }

    /**
     * Walks over a column of a SNMP table.
     *
     * @param SnmpPollerInterface $poller
     *
     * @return stdClass
     */
    private function walk(SnmpPollerInterface $poller) : stdClass
    {
        $snmp_walk = [];

        $oid_count = count($poller->getOids());
        $oid_blank = 0;

        // if ($oid_count > Config::get('snmp.max_oids', 20)) {
        //     throw new \Exception("max oid is greater than specified");
        // }

        foreach ($poller->getOids() as $key => $oid) {
            try {
                $query = $this->session->walk($oid, true);
            } catch (\Exception $e) {
                $query = false;
                $oid_blank++;
            }

            if (! $query) {
                $snmp_walk[$key] = $this->returnErrorData();
            } else {
                foreach ($query as $row => $value) {
                    $parser = new SnmpParser($value);

                    $snmp_walk[$key][$row] = $parser->parse();
                }
            }
        }

        if ($oid_count === $oid_blank) {
            return (object) [
                'data' => $snmp_walk,
                'poller' => get_class($poller),
                'result' => 'Failed',
                'table' => $poller->getTable(),
            ];
        }

        return (object) [
            'data' => $poller->isTable() ? $this->transposeTable($snmp_walk) : $snmp_walk,
            'poller' => get_class($poller),
            'result' => $query ? 'OK' : 'Failed',
            'table' => $poller->getTable(),
        ];
    }

    /**
     * Returns the name of the Poller class.
     *
     * @param SnmpPollerInterface $poller
     *
     * @return string
     */
    private function getPollerClassName(SnmpPollerInterface $poller) : string
    {
        return (new ReflectionClass($poller))->getShortName();
    }
}