<?php

namespace PabloTejada\MailgunOptions\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Database\ConsoleServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ConsoleServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->push('view.paths', __DIR__ . '/views');
        //        $app['config']->push('mail.paths', __DIR__ . '/views/mail');
    }
}
