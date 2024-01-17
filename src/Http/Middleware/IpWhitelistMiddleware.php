<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class IpWhitelistMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $ips = config('openswoole-stats.ip_whitelist');

        if (!empty($ips)) {
            abort_unless(IpUtils::checkIp($request->ip(), $ips), 403);
        }

        return $next($request);
    }
}
