<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes;

    use PsychoB\WebFramework\Collection\Opt;
    use PsychoB\WebFramework\Config\ConfigManager;
    use PsychoB\WebFramework\DependencyInjector\InjectorInterface;
    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Utility\Str;
    use PsychoB\WebFramework\Web\Routes\Http\Header;
    use PsychoB\WebFramework\Web\Routes\Http\Middleware\MiddlewareExecutor;
    use PsychoB\WebFramework\Web\Routes\Http\Request;
    use PsychoB\WebFramework\Web\Routes\Http\RequestBuilder;
    use PsychoB\WebFramework\Web\Routes\Http\Response;
    use PsychoB\WebFramework\Web\Routes\Http\Routes\ErrorMatcherRoute;
    use PsychoB\WebFramework\Web\Routes\Http\Routes\ErrorRoute;
    use Ramsey\Uuid\Uuid;

    class RouteManager
    {
        protected bool $routesLoaded = false;
        /** @var Route[] */
        protected array $routes = [];
        protected ConfigManager $config;
        protected InjectorInterface $injector;

        public function __construct(ConfigManager $config, InjectorInterface $injector)
        {
            $this->config = $config;
            $this->injector = $injector;
        }

        /** @noinspection GlobalVariableUsageInspection */
        public function getRequestFromEnvironment(): Request
        {
            /// TODO: Check $_POST['__ppp_method'] to override Request
            return RequestBuilder::new()
                ->method($_SERVER['REQUEST_METHOD'])
                ->uri($_SERVER['REQUEST_URI'])
                ->headers(
                    collect($_SERVER)
                        ->filter(fn($value, $key) => !Str::startsWith($key, 'HTTP_'))
                        ->map(function ($value, &$key) {
                            $key = Str::substr($key, 5);
                            $key = Str::toLower($key);
                            $key = Str::replace($key, '_', ' ');
                            $key = Str::upperCaseWords($key);
                            $key = Str::replace($key, ' ', '-');
                        }, Opt::REWRITE_KEYS | Opt::IGNORE_RETURN)
                        ->map(fn($value, $key) => Header::fromString($key, $value), Opt::RECOUNT_KEY)
                        ->toArray(),
                )
                ->get($_GET)
                ->post($_POST)
                ->files($_FILES)
                ->body((function () {
                    $in = fopen('php://input', 'r');
                    return stream_get_contents($in);
                })())
                ->toRequest();
        }

        public function handleRequest(Request $request): Response
        {
            if (!$this->areRoutesLoaded()) {
                $this->loadRoutes();
            }

            $route = $this->matchRoute($request);

            if ($route === null) {
                $route = new ErrorMatcherRoute($request->getUri());
            }

            $middlewareAliases = $this->config->options()->get('http.middleware.aliases', []);
            $middlewareIgnored = $this->config->options()->get('http.middleware.ignored', []);
            $middlewareImplied = $this->config->options()->get('http.middleware.implied', []);

            $middleware = collect($middlewareImplied)
                ->sort(function (string $left, string $right): int {
                    $lPriority = call_user_func([$left, 'getPriority']);
                    $rPriority = call_user_func([$right, 'getPriority']);

                    return $lPriority <=> $rPriority;
                })->toArray();

            $response = $this->executeMiddleware($route->getRoute(), $request, $middleware);

            return $response;
        }

        private function areRoutesLoaded(): bool
        {
            return $this->routesLoaded;
        }

        private function loadRoutes()
        {
            /** @var \SplFileInfo $file */
            foreach ($this->config->routes() as $file) {
                $this->loadRoutesFromFile($file->getPathname());
            }
            $this->routesLoaded = true;
        }

        private function loadRoutesFromFile(string $path): void
        {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $no => $line) {
                $regExp =
                    /** @lang RegExp */
                    '/^' .
                    '(?P<ROUTE_METHOD>' .
                        '(?:GET|POST|PUT|DELETE|HEAD|OPTIONS|PATCH)' .
                        '(?:\\|(?:GET|POST|PUT|DELETE|HEAD|OPTIONS|PATCH))*?' .
                    ')\s+?' .
                    '(?P<ROUTE_URI>[^\s]+)\s+?' .
                    '(?:EXECUTE\s+?(?:' .
                        '(?P<EXECUTE_FILL>:(?P<EXECUTE_FILL_CTRL>[a-zA-Z0-9\\\\]+)->(?P<EXECUTE_FILL_ACTION>[a-zA-Z0-9\\\\]+))|' .
                        '(?P<EXECUTE_RAW>(?P<EXECUTE_RAW_CTRL>[a-zA-Z0-9\\\\]+)->(?P<EXECUTE_RAW_ACTION>[a-zA-Z0-9\\\\]+))' .
                    '))?' .
                    '(?:\s+?NAME\s+?(?P<ROUTE_NAME>[^\s]+))?' .
                    '\s*?$/';

                if (preg_match($regExp, $line, $m, PREG_UNMATCHED_AS_NULL)) {
                    $method = Str::split($m['ROUTE_METHOD'], '|');
                    $uri = $m['ROUTE_URI'];
                    $name = $m['ROUTE_NAME'] ? 'spec://' . $m['ROUTE_NAME'] : 'anon://' . Uuid::uuid4()->toString();

                    $ctrl = null;
                    $action = null;

                    if ($m['EXECUTE_FILL']) {
                        $ctrl = sprintf('%s\\Http\\Controllers\\%sController', $this->config->options()->get('app.namespace', 'App\\Http\\Controller'), $m['EXECUTE_FILL_CTRL']);
                        $action = $m['EXECUTE_FILL_ACTION'];
                    } else {
                        $ctrl = $m['EXECUTE_RAW_CTRL'];
                        $action = $m['EXECUTE_RAW_ACTION'];
                    }

                    $this->routes[] = new Route($name, $method, $uri, $ctrl, $action);
                }
            }
        }

        private function matchRoute(Request $request): ?RouteMatcher
        {
            foreach ($this->routes as $route) {
                if (!Arr::inArray($route->getMethod(), $request->getMethod())) {
                    continue;
                }

                $match = $route->matchUri($request->getUri());

                if ($match === null) {
                    continue;
                }

                return $match;
            }

            return null;
        }

        private function executeMiddleware(Route $route, Request $request, array $middleware): Response
        {
            try {
                $this->currentRoute[] = $route;

                $executor = new MiddlewareExecutor(
                    collect($middleware)
                        ->map(fn($middleware) => $this->injector->make($middleware))
                        ->toArray(), $route
                );

                return $executor->start($request);
            } finally {
                Arr::pop($this->currentRoute);
            }
        }

        private array $currentRoute = [];

        public function getCurrentRoute(): ?Route
        {
            return count($this->currentRoute) ? Arr::last($this->currentRoute) : null;
        }
    }
