<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Route;

    use PsychoB\WebFramework\Collection\Enum\SortDirectionEnum;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Str;
    use PsychoB\WebFramework\Web\Enum\HttpMethodEnum;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteException;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteNameException;
    use PsychoB\WebFramework\Web\Http\Controllers\BasicErrorController;
    use PsychoB\WebFramework\Web\Http\Middlewares\MiddlewareInterface;
    use PsychoB\WebFramework\Web\Http\Request;
    use PsychoB\WebFramework\Web\Http\RequestContext;
    use PsychoB\WebFramework\Web\Http\Response;
    use PsychoB\WebFramework\Web\Http\Route\Builder\RouteGroupInterface;

    class RouteManager implements RouteGroupInterface
    {
        /** @var Route[] */
        private array $routes = [];
        /** @var Request[] */
        private array $flyingRequests = [];

        public function addRoute(
            array $method,
            string $uri,
            array $ctrl,
            array $middlewares = [],
            ?string $name = null
        ): void
        {
            $newRoute = new Route($method, $uri, $ctrl, $name, $middlewares);

            if ($conflicting = Arr::firstOf($this->routes, fn(Route $r) => $this->_checkIfConflicting($newRoute, $r))) {
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
            /** @noinspection GlobalVariableUsageInspection */
            return new Request(
                Str::toUpper($_SERVER['REQUEST_METHOD']),
                $_SERVER['REQUEST_URI'],
                Arr::stream($_SERVER)
                    ->filterKey(fn ($key) => Str::sub($key, 0, 5) === 'HTTP_')
                    ->toArray()
            );
        }

        public function matchRouteForRequest(Request $request): FilledRoute
        {
            foreach ($this->routes as $route) {
                $filled = $route->match($request);

                if ($filled !== null) {
                    return $filled;
                }
            }

            return new FilledRoute($this->get404Route($request), $request);
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

        public function get404Route(Request $request): Route
        {
            return new Route(
                HttpMethodEnum::all(),
                $request->getUri(),
                [BasicErrorController::class, 'routeNotFound'],
                'ppp.error.404'
            );
        }

        public function executeRoute(FilledRoute $route): Response
        {
            $middlewares = $this->createMiddlewaresFrom($route->getMiddlewares());
            $middlewareStack = $this->createMiddlewareStackFrom($middlewares);
            $requestContext = new RequestContext();

            return $middlewareStack->next($route->getRequest(), $middlewareStack, $requestContext);
        }

        private function createMiddlewaresFrom(array $middlewaresFromRoute): array
        {
            return Arr::stream($middlewaresFromRoute)
                ->map(function ($value): MiddlewareInterface {
                    if (Str::is($value)) {
                        return $this->createMiddlewareProxy($value);
                    }

                    return $this->createMiddlewareProxy($value['class'], $value['arguments']);
                })
                ->sortByField('getDefaultPriority', SortDirectionEnum::DESCENDING, false)
                ->toArray();
        }
    }
