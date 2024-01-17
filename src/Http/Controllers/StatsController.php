<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats\Http\Controllers;

use Dandysi\Laravel\OpenSwooleStats\Http\Services\StatService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Swoole\Http\Server;

class StatsController
{
    public function __invoke()
    {
        $service = app(StatService::class);

        if (!config('openswoole-stats.openmetrics')) {
            return JsonResponse::fromJsonString($service->getJson());
        }

        return (new Response($service->getOpenmetrics()))
            ->header('Content-Type', 'text/plain')
        ;
    }
}
