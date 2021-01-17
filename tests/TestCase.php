<?php

namespace Ghebby\LaravelHfm\Tests;

use Ghebby\LaravelHfm\LaravelHfmServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelHfmServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // non Environment properties to be set
    }
}
