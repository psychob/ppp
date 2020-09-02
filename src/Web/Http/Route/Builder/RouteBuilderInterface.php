<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route\Builder;

    interface RouteBuilderInterface
    {
        public function group(string $uri): RouteBuilderGroupInterface;

        public function get(string $uri, array $ctr): RouteBuilderRouteInterface;
        public function post(string $uri, array $ctr): RouteBuilderRouteInterface;
        public function put(string $uri, array $ctr): RouteBuilderRouteInterface;
        public function delete(string $uri, array $ctr): RouteBuilderRouteInterface;
    }
