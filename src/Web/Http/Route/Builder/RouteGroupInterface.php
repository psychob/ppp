<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route\Builder;

    interface RouteGroupInterface
    {
        public function addRoute(array $method, string $uri, array $ctrl, ?string $name = null);
    }
