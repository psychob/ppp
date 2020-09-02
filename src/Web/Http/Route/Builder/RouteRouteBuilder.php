<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route\Builder;

    class RouteRouteBuilder implements RouteBuilderRouteInterface
    {
        private RouteGroupInterface $father;
        private string $uri;
        private array $controller;
        private string $method;

        public function __construct(RouteGroupInterface $groupInfo, string $uri, array $ctrl, string $method)
        {
            $this->father = $groupInfo;
            $this->uri = $uri;
            $this->controller = $ctrl;
            $this->method = $method;
        }

        public function __destruct()
        {
            $this->father->addRoute([$this->method], $this->uri, $this->controller);
        }
    }
