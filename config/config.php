<?php

return [
    "enabled" => env('OPENSWOOLE_STATS_ENABLED', true),

    /**
     * Change the endpoint.
     */
    "url" => 'openswoole-stats',

    /**
     * Return openmetrics format, if false json will be returned.
     */
    "openmetrics" => true,

    /**
     * What IPs can access the endpoint.
     */
    "ip_whitelist" => [
        // empty allows all
    ],

    /**
     * Default middleware to restrict access to the endpoint via above ips
     */
    "middleware" => \Dandysi\Laravel\OpenSwooleStats\Http\Middleware\IpWhitelistMiddleware::class,
];
