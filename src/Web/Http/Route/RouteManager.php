<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route;

    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteNameException;
    use PsychoB\WebFramework\Web\Http\Request;
    use PsychoB\WebFramework\Web\Http\Response;

    class RouteManager
    {
        /** @var Route[] */
        private array $routes = [];
        /** @var Request[] */
        private array $flyingRequests = [];

        public function addRoute(array $methods, string $uri, array $controller, ?string $name = null): void
        {
            if ($name && ($or = Arr::firstOf($this->routes, fn (Route $r) => $r->getName() === $name))) {
                throw new DuplicateRouteNameException($name, $or);
            }

            $this->routes[] = new Route($methods, $uri, $controller, $name);
        }

        public function getCurrentRequest(): Request
        {
            if (Arr::len($this->flyingRequests) === 0) {
                return $this->getGlobalRequest();
            }

            return Arr::fetchBack($this->flyingRequests);
        }

        public function getGlobalRequest(): Request
        {
            return new Request();
        }

        public function matchRouteForRequest(Request $request): FilledRoute
        {
        }
    }
