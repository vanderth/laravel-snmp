<?php

use SNMP;

return [
    'session' => [
        'default' => [
            'host' => env('SNMP_HOST', 'localhost'),
            'community' => env('SNMP_COMMUNITY', 'public'),
            'version' => SNMP::VERSION_2c
        ]
    ],

    /**
     * Set Max OID Input GET/SET/GETBULK Request
     * 
     * @var int
     */
    'max_oids' => 20,

    /**
     * Control OID Output Format
     * 
     * @var int
     */
    'oid_output_format' => SNMP_OID_OUTPUT_NUMERIC
];