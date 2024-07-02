<?php

use SNMP;

if (! function_exists('snmp_session')) {

    /**
     * @param int
     * @param string
     * @param string
     * 
     * @return SNMP
     */
    function snmp_session(int $version = SNMP::VERSION_2c, string $hostname, string $community)
    {
        $session = new SNMP($version, $hostname, $community);
        $session->exceptions_enabled = true;
        $session->oid_output_format = SNMP_OID_OUTPUT_NUMERIC;
        $session->quick_print = true;
        $session->valueretrieval = SNMP_VALUE_OBJECT | SNMP_VALUE_PLAIN;

        return $session;
    }
}