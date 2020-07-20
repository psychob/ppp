<?php

    return [
        'aliases' => [],
        'ignored' => [
            \PsychoB\WebFramework\Web\Routes\Http\Middleware\MiddlewareExecutor::class,
        ],
        'implied' => [
            \PsychoB\WebFramework\Web\Routes\Http\Middleware\ConstructResponseMiddleware::class
        ],
    ];
