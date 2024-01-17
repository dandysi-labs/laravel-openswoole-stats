<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats\Tests\Unit\Services;

use Dandysi\Laravel\OpenSwooleStats\Http\Services\StatService;
use Dandysi\Laravel\OpenSwooleStats\Tests\Unit\BaseTestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Swoole\Http\Server;

class StatServiceTest extends BaseTestCase
{
    public function setUp(): void
    {
        $container = new Container();
        Container::setInstance($container);
        Facade::setFacadeApplication($container);
    }

    public function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);
        Mockery::close();
    }

    #[Test]
    public function it_throws_an_excepion_if_server_not_bound()
    {
        $service = new StatService();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Server not bound, please ensure you are running through octane.');
        $service->getJson();
    }

    #[Test]
    public function it_returns_stats_for_json()
    {
        $result = 'json';
        $mock = Mockery::mock(Server::class);
        $mock->shouldReceive('stats')->with(1)->andReturn($result);
        Container::getInstance()->instance(Server::class, $mock);
        $service = new StatService();
        $this->assertSame($result, $service->getJson());
    }

    #[Test]
    public function it_returns_stats_for_openmetrics()
    {
        $result = 'openmetrics';
        $mock = Mockery::mock(Server::class);
        $mock->shouldReceive('stats')->with(2)->andReturn($result);
        Container::getInstance()->instance(Server::class, $mock);
        $service = new StatService();
        $this->assertSame($result, $service->getOpenmetrics());
    }
}
