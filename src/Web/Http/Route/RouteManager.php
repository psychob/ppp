<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route;

    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteException;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteNameException;
    use PsychoB\WebFramework\Web\Http\Request;

    class RouteManager
    {
        /** @var Route[] */
        private array $routes = [];
        /** @var Request[] */
        private array $flyingRequests = [];

        public function addRoute(array $methods, string $uri, array $controller, ?string $name = null): void
        {
            $newRoute = new Route($methods, $uri, $controller, $name);

            if ($conflicting = Arr::firstOf($this->routes, fn (Route $r) => $this->_checkIfConflicting($newRoute, $r))) {
                if ($conflicting->getName() === $newRoute->getName()) {
                    throw new DuplicateRouteNameException($conflicting->getName(), $newRoute, $conflicting);
                }

                throw new DuplicateRouteException($newRoute, $conflicting);
            }

            $this->routes[] = $newRoute;
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

        private function _checkIfConflicting(Route $new, Route $current): bool
        {
            if ($new->getName() !== null && $new->getName() === $current->getName()) {
                return true;
            }

            return Arr::notEmpty(Arr::valuesIntersects($new->getMethods(), $current->getMethods())) &&
                $new->getUri() === $current->getUri();
        }

        public function getRouteCount(): int
        {
            return Arr::len($this->routes);
        }
    }
