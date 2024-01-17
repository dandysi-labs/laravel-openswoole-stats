<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats\Tests\Feature;

use Dandysi\Laravel\OpenSwooleStats\Http\Services\StatService;
use Dandysi\Laravel\OpenSwooleStats\Tests\PackageTestCase;
use Mockery\MockInterface;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use PHPUnit\Framework\Attributes\Test;

class EndpointTest extends PackageTestCase
{
    const DEFAULT_URL = '/openswoole-stats';
    const OPENMETRICS_DATA = 'testdata';
    const JSON_DATA = '{"test":true}';

    #[Test]
    #[DefineEnvironment('useDisabled')]
    public function it_can_be_diasbled(): void
    {
        $response = $this->get(self::DEFAULT_URL);
        $response->assertNotFound();
    }

    #[Test]
    #[DefineEnvironment('useIpWhitelist')]
    public function it_can_deny_access_to_non_whitelisted_ips(): void
    {
        $response = $this->get(self::DEFAULT_URL,['REMOTE_ADDR' => '10.0.0.2']);
        $response->assertForbidden();
    }

    #[Test]
    #[DefineEnvironment('useIpWhitelist')]
    public function it_allows_access_to_whitelisted_ips(): void
    {
        $this->mockStatService();
        $response = $this->get(self::DEFAULT_URL,['REMOTE_ADDR' => '10.0.0.1']);
        $this->assertStatsResponse($response);
    }

    #[Test]
    #[DefineEnvironment('useDifferentUrl')]
    public function it_can_use_a_diff_url(): void
    {
        $this->mockStatService();
        $response = $this->get('/stats');
        $this->assertStatsResponse($response);
    }

    #[Test]
    #[DefineEnvironment('useJsonFormat')]
    public function it_can_return_json(): void
    {
        $this->mockStatService(false);
        $response = $this->get(self::DEFAULT_URL);
        $this->assertStatsResponse($response, false);
    }

    protected function mockStatService(bool $openmetrics=true)
    {
        $this->partialMock(StatService::class, function (MockInterface $mock) use ($openmetrics) {
            $mock
                ->shouldReceive($openmetrics ? 'getOpenmetrics' : 'getJson')
                ->andReturn($openmetrics ? self::OPENMETRICS_DATA : self::JSON_DATA)
            ;
        });
    }

    protected function assertStatsResponse($response, bool $openmetrics = true): void
    {
        $response->assertSuccessful();

        if ($openmetrics) {
            $response->assertContent(self::OPENMETRICS_DATA);
            return;
        }

        $response->assertJson(['test'=>true]);
    }

    private function createServer(): void
    {
        $this->server = new Server();
    }

    protected function useJsonFormat($app)
    {
        $app['config']->set('openswoole-stats.openmetrics', false);
    }

    protected function useDisabled($app)
    {
        $app['config']->set('openswoole-stats.enabled', false);
    }

    protected function useDifferentUrl($app)
    {
        $app['config']->set('openswoole-stats.url', 'stats');
    }

    protected function useIpWhitelist($app)
    {
        $app['config']->set('openswoole-stats.ip_whitelist', '10.0.0.1');
    }
}
