<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats\Http\Services;

use Swoole\Http\Server;
use RuntimeException;

class StatService
{
    private function getStats($format): string
    {
        throw_unless(
            app()->bound(Server::class),
            RuntimeException::class,
            'Server not bound, please ensure you are running through octane.'
        );

        return app()->get(Server::class)->stats($format);
    }
    public function getOpenmetrics(): string
    {
        return $this->getStats(2);
    }

    public function getJson(): string
    {
        return $this->getStats(1);
    }
}
