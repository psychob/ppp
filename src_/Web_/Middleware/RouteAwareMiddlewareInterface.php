<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Middleware;

    use PsychoB\WebFramework\Web_\Route\Route;

    interface RouteAwareMiddlewareInterface
    {
        public function setRoute(Route $route): void;
    }
