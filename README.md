# Laravel SNMP Poller Package

This Laravel package allows you to run SNMP queries to the snmp-agent of network hosts through laravel applications.

Before you run any SNMP query in your [Laravel](https://laravel.com/) application, you must install snmp in the operating system and enable the PHP extension **ext-php** in the php.ini to make queries.

- [Laravel SNMP Poller Package](#laravel-snmp-poller-package)
  - [Installation](#installation)
    - [Requirements](#requirements)
    - [Composer Install](#composer-install)
    - [Publish vendor assets](#publish-vendor-assets)
  - [Usage](#usage)
    - [Single SNMP Poller class](#single-snmp-poller-class)
    - [Multiple SNMP Pollers classes](#multiple-snmp-pollers-classes)
  - [Testing](#testing)
  - [Changelog](#changelog)
  - [Security](#security)
  - [Standards](#standards)
  - [Credits](#credits)
  - [License](#license)

## Installation

### Requirements

At the Operating System level, you need to install net-snmp.

**Debian**

```bash
apt-get install -y snmp
```

**Centos / RHEL**

```bash
yum install \
  net-snmp.x86_64 \
  net-snmp-agent-libs.x86_64 \
  net-snmp-libs.x86_64 \
  php-snmp.x86_64
```

At php.ini enable the extension ext-php.

### Composer Install

You can install the package via composer:

```bash
composer require acamposm/snmp-poller
```

### Publish vendor assets

Running this command, you can publish the vendor assets. This allows to modify the default package configuration.

```bash
php artisan snmp:install
```

## Usage

### Single SNMP Poller class

```php
use Vanderth\Snmp\SnmpPoller;
use Vanderth\Snmp\Pollers\IfTablePoller;
use SNMP;

$session = new SNMP(SNMP::VERSION_2C, '192.168.10.254', 'csnmpv2c');

$poller = new SnmpPoller();

$poller->setSnmpSession($session)->addPoller(IfTablePoller::class)->run();
```

The output of an SNMP query to the snmp-agent of a network host with a single SNMP Poller class returns an array of objects with the data inside the data field.

**Note**: For demonstration purposes, all ports between 3 and 22 have been removed from the output of the query.

```
=> [
  "IfTablePoller" => {#653
    +"data": [
      1 => [
        "ifIndex" => 1,
        "ifDescr" => "",
        "ifType" => 6,
        "ifMtu" => 1500,
        "ifSpeed" => 1000000000,
        "ifPhysAddress" => "90:6C:AC:62:82:5B",
        "ifAdminStatus" => 1,
        "ifOperStatus" => 1,
        "ifLastChange" => 0,
        "ifInOctets" => 3808861579,
        "ifInUcastPkts" => 1130144532,
        "ifInDiscards" => 0,
        "ifInErrors" => 2,
        "ifInUnknownProtos" => 0,
        "ifOutOctets" => 1986123900,
        "ifOutUcastPkts" => 735043481,
        "ifOutDiscards" => 0,
        "ifOutErrors" => 0,
      ],
      2 => [
        "ifIndex" => 2,
        "ifDescr" => "",
        "ifType" => 6,
        "ifMtu" => 1500,
        "ifSpeed" => 0,
        "ifPhysAddress" => "90:6C:AC:62:82:5C",
        "ifAdminStatus" => 1,
        "ifOperStatus" => 2,
        "ifLastChange" => 0,
        "ifInOctets" => 0,
        "ifInUcastPkts" => 0,
        "ifInDiscards" => 0,
        "ifInErrors" => 0,
        "ifInUnknownProtos" => 0,
        "ifOutOctets" => 0,
        "ifOutUcastPkts" => 0,
        "ifOutDiscards" => 0,
        "ifOutErrors" => 0,
      ],
      3 => [ ... ],
      4 => [ ... ],
      5 => [ ... ],
      6 => [ ... ],
      7 => [ ... ],
      8 => [ ... ],
      9 => [ ... ],
      10 => [ ... ],
      11 => [ ... ],
      12 => [ ... ],
      13 => [ ... ],
      14 => [ ... ],
      15 => [ ... ],
      16 => [ ... ],
      17 => [ ... ],
      18 => [ ... ],
      19 => [ ... ],
      20 => [ ... ],
      21 => [ ... ],
      22 => [ ... ],
    ],
    +"poller": "Vanderth\Snmp\Pollers\IfTablePoller",
    +"result": "OK",
    +"table": "ifTable",
  },
]
```

### Multiple SNMP Pollers classes

```php
use Vanderth\Snmp\SnmpPoller;
use Vanderth\Snmp\Pollers\IfTablePoller;
use Vanderth\Snmp\Pollers\IfExtendTablePoller;
use SNMP;

$session = new SNMP(SnmpPoller::V2c, 'localhost', 'public');

$poller = new SnmpPoller();

$pollerClasses = [
   IfTablePoller::class,
   IfExtendTablePoller::class,
];

$poller->setSnmpSession($session)
       ->addPollers($pollerClasses)
       ->run();
```

The output of an SNMP query to the snmp-agent of a network host with multiple SNMP Poller classes returns an array of objects with the data inside the data field.

**Note**: For demonstration purposes, all ports between 3 and 22 have been removed from the output of the query.

```
=> [
  "IfTablePoller" => {
    +"data" => [
      1 => [
        "ifIndex" => 1,
        "ifDescr" => "",
        "ifType" => 6,
        "ifMtu" => 1500,
        "ifSpeed" => 1000000000,
        "ifPhysAddress" => "90:6C:AC:62:82:5B",
        "ifAdminStatus" => 1,
        "ifOperStatus" => 1,
        "ifLastChange" => 0,
        "ifInOctets" => 2518775779,
        "ifInUcastPkts" => 1129071509,
        "ifInDiscards" => 0,
        "ifInErrors" => 2,
        "ifInUnknownProtos" => 0,
        "ifOutOctets" => 1941896771,
        "ifOutUcastPkts" => 734653319,
        "ifOutDiscards" => 0,
        "ifOutErrors" => 0,
      ],
      2 => [
        "ifIndex" => 2,
        "ifDescr" => "",
        "ifType" => 6,
        "ifMtu" => 1500,
        "ifSpeed" => 0,
        "ifPhysAddress" => "90:6C:AC:62:82:5C",
        "ifAdminStatus" => 1,
        "ifOperStatus" => 2,
        "ifLastChange" => 0,
        "ifInOctets" => 0,
        "ifInUcastPkts" => 0,
        "ifInDiscards" => 0,
        "ifInErrors" => 0,
        "ifInUnknownProtos" => 0,
        "ifOutOctets" => 0,
        "ifOutUcastPkts" => 0,
        "ifOutDiscards" => 0,
        "ifOutErrors" => 0,
      ],
      3 => [ ... ],
      4 => [ ... ],
      5 => [ ... ],
      6 => [ ... ],
      7 => [ ... ],
      8 => [ ... ],
      9 => [ ... ],
      10 => [ ... ],
      11 => [ ... ],
      12 => [ ... ],
      13 => [ ... ],
      14 => [ ... ],
      15 => [ ... ],
      16 => [ ... ],
      17 => [ ... ],
      18 => [ ... ],
      19 => [ ... ],
      20 => [ ... ],
      21 => [ ... ],
      22 => [ ... ],
    ],
    +"poller": "Vanderth\Snmp\Pollers\IfTablePoller",
    +"result": "OK",
    +"table": "ifTable",
  },
  "IfExtendTablePoller" => {
    +"data" => [
      1 => [
        "ifName" => "wan1",
        "ifInMulticastPkts" => 0,
        "ifInBroadcastPkts" => 0,
        "ifOutMulticastPkts" => 0,
        "ifOutBroadcastPkts" => 0,
        "ifHCInOctets" => 1123505241577,
        "ifHCInUcastPkts" => 1129071513,
        "ifHCInMulticastPkts" => 0,
        "ifHCInBroadcastPkts" => 0,
        "ifHCOutOctets" => 255344967343,
        "ifHCOutUcastPkts" => 734653320,
        "ifHCOutMulticastPkts" => 0,
        "ifHCOutBroadcastPkts" => 0,
        "ifLinkUpDownTrapEnable" => 1,
        "ifHighSpeed" => 1000,
        "ifPromiscuousMode" => 2,
        "ifConnectorPresent" => 1,
        "ifAlias" => "",
        "ifCounterDiscontinuityTime" => 0,
      ],
      2 => [
        "ifName" => "wan2",
        "ifInMulticastPkts" => 0,
        "ifInBroadcastPkts" => 0,
        "ifOutMulticastPkts" => 0,
        "ifOutBroadcastPkts" => 0,
        "ifHCInOctets" => 0,
        "ifHCInUcastPkts" => 0,
        "ifHCInMulticastPkts" => 0,
        "ifHCInBroadcastPkts" => 0,
        "ifHCOutOctets" => 0,
        "ifHCOutUcastPkts" => 0,
        "ifHCOutMulticastPkts" => 0,
        "ifHCOutBroadcastPkts" => 0,
        "ifLinkUpDownTrapEnable" => 1,
        "ifHighSpeed" => 0,
        "ifPromiscuousMode" => 2,
        "ifConnectorPresent" => 1,
        "ifAlias" => "",
        "ifCounterDiscontinuityTime" => 0,
      ],
      3 => [ ... ],
      4 => [ ... ],
      5 => [ ... ],
      6 => [ ... ],
      7 => [ ... ],
      8 => [ ... ],
      9 => [ ... ],
      10 => [ ... ],
      11 => [ ... ],
      12 => [ ... ],
      13 => [ ... ],
      14 => [ ... ],
      15 => [ ... ],
      16 => [ ... ],
      17 => [ ... ],
      18 => [ ... ],
      19 => [ ... ],
      20 => [ ... ],
      21 => [ ... ],
      22 => [ ... ],
    ],
    +"poller" => "Vanderth\Snmp\Pollers\IfExtendedTablePoller",
    +"result" => "OK",
    +"table" => "ifXTable",
  }
]
```

## Testing

For running the tests, in the console run:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email juvan.nayoan@gmail.com instead of using the issue tracker.

## Standards

The php package IPv4 Address Converter, comply with the next standards:

- [PSR-1 - Basic Coding Standard](http://www.php-fig.org/psr/psr-1/)
- [PSR-4 - Autoloading Standard](http://www.php-fig.org/psr/psr-4/)
- [PSR-12 - Extended Coding Style Guide](https://www.php-fig.org/psr/psr-12/)

## Credits

This package based on [snmp-poller](https://github.com/angelcamposm/snmp-poller/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
