<?php

    use PsychoB\WebFramework\Web\Http\Controllers\FrameworkController;
    use PsychoB\WebFramework\Web\Http\Middlewares\EnableForSpecificEnvironmentMiddleware;
    use PsychoB\WebFramework\Web\Http\Route\Builder\RouteBuilderInterface;

    return function (RouteBuilderInterface $routes) {
        $routes->group('/__ppp/')
               ->middleware(EnableForSpecificEnvironmentMiddleware::class, ['local', 'debug'])
               ->routes(function (RouteBuilderInterface $router) {
                   $router->get('/info/pretty', [FrameworkController::class, 'getFrameworkInformationView']);
                   $router->get('/info', [FrameworkController::class, 'getFrameworkInformationApi']);
               });
    };
