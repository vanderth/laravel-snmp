<?php

namespace Vanderth\Snmp\Providers;

use Illuminate\Support\ServiceProvider;
use Vanderth\Snmp\Facades\SnmpPoller;

class SnmpPollerServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->publishConfig();
    }

    public function register() : void
    {
        $this->app->bind('laravel-snmp', function ($app) {
            return new SnmpPoller();
        });
    }

    private function publishConfig() : void
    {
        $this->publishes([
            __DIR__ . '/../../config/snmp.php' => $this->app->configPath('snmp.php')
        ], 'config');
    }
}