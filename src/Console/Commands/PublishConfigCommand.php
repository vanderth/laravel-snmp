<?php

namespace Vanderth\Snmp\Console\Commands;

use Illuminate\Console\Command;

class PublishConfigCommand extends Command
{
    protected $signature = 'snmp:install';

    protected $description = 'Install SNMP Poller package';

    public function handle()
    {
        if (file_exists(config_path('snmp.php'))) {
            $this->hidden = true;
        }

        $this->info('Installing Snmp Poller Package...');

        $this->info('Publishing package files...');

        $this->call('vendor:publish', [
            '--provider' => 'Vanderth\Snmp\Providers\SnmpPollerServiceProvider',
        ]);

        $this->info('Snmp Poller Package installed.');

        return 1;
    }
}