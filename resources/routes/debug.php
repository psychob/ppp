<?php

    return function (RootRouteBuilderInterface $routes) {
        $routes->group('__ppp/c/debug', function (RouteBuilderInterface $routes) {
            $routes->get('/', [DebugController::class, 'mainPage']);
        });
    };
