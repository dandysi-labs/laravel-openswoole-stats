<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats\Tests;

use Dandysi\Laravel\OpenSwooleStats\OpenSwooleStatsServiceProvider;
use Orchestra\Testbench\TestCase;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            OpenSwooleStatsServiceProvider::class,
        ];
    }
}
