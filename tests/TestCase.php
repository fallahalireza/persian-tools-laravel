<?php

namespace FallahAlireza\PersianTools\Tests;

use FallahAlireza\PersianTools\PersianToolsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [PersianToolsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('persian-tools.register_rules', true);
        $app['config']->set('persian-tools.accept_persian_numbers', false);
    }
}
