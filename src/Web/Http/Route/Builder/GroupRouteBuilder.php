<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route\Builder;

    use PsychoB\WebFramework\Web\Enum\HttpMethodEnum;

    class GroupRouteBuilder implements RouteBuilderGroupInterface, RouteGroupInterface
    {
        private RouteGroupInterface $father;
        private string $uri;

        public function __construct(RouteGroupInterface $groupInfo, string $uri)
        {
            $this->father = $groupInfo;
            $this->uri = $uri;
        }

        public function middleware(string $aliasOrClass, ...$arguments): RouteBuilderGroupInterface
        {
            /// TODO
        }

        public function addRoute(array $method, string $uri, array $ctrl, ?string $name = null)
        {
            $this->father->addRoute($method, $uri, $ctrl, $name);
        }

        public function routes(callable $definitions): void
        {
            $definitions($this);
        }

        public function group(string $uri): RouteBuilderGroupInterface
        {
            return new GroupRouteBuilder($this, $uri);
        }

        public function get(string $uri, array $ctr): RouteBuilderRouteInterface
        {
            return new RouteRouteBuilder($this, $uri, $ctr, HttpMethodEnum::GET);
        }

        public function post(string $uri, array $ctr): RouteBuilderRouteInterface
        {
            return new RouteRouteBuilder($this, $uri, $ctr, HttpMethodEnum::POST);
        }

        public function put(string $uri, array $ctr): RouteBuilderRouteInterface
        {
            return new RouteRouteBuilder($this, $uri, $ctr, HttpMethodEnum::PUT);
        }

        public function delete(string $uri, array $ctr): RouteBuilderRouteInterface
        {
            return new RouteRouteBuilder($this, $uri, $ctr, HttpMethodEnum::DELETE);
        }
    }
