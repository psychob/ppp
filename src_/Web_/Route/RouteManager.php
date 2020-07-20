<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Route;

    use PsychoB\WebFramework\Config\ConfigManager;
    use PsychoB\WebFramework\Debug\Clock;
    use PsychoB\WebFramework\Environment\ApplicationInterface;
    use PsychoB\WebFramework\Injector\InjectorInterface;
    use PsychoB\WebFramework\Tokenizer\Tokenizer;
    use PsychoB\WebFramework\Utils\Arr;
    use PsychoB\WebFramework\Web_\Http\Request;
    use PsychoB\WebFramework\Web_\Http\Response;
    use PsychoB\WebFramework\Web_\Middleware\LastMiddleware;
    use PsychoB\WebFramework\Web_\Middleware\MiddlewareContextName;
    use PsychoB\WebFramework\Web_\Middleware\MiddlewareInterface;
    use PsychoB\WebFramework\Web_\Middleware\NextMiddleware;
    use PsychoB\WebFramework\Web_\Middleware\RouteAwareMiddlewareInterface;
    use Ramsey\Uuid\Uuid;
    use SplFileInfo;

    class RouteManager
    {
        protected ConfigManager $config;
        /** @var Route[] */
        protected array $routes = [];
        protected Clock $timer;
        protected string $baseNamespace;
        protected ApplicationInterface $app;

        /**
         * RouteManager constructor.
         *
         * @param ConfigManager $config
         * @param Clock $timer
         * @param ApplicationInterface $app
         */
        public function __construct(ConfigManager $config, Clock $timer, ApplicationInterface $app)
        {
            $this->config = $config;
            $this->timer = $timer;
            $this->app = $app;
            $this->baseNamespace = $app->getAppNamespace();
        }

        private function loadedRoutes(): bool
        {
            return false;
        }

        private function loadRoutes(): void
        {
            $section = $this->timer->section('LOAD_ROUTES');
            $parsedRoutes = [];

            /** @var SplFileInfo $routeFile */
            foreach ($this->config->fetch('routes') as $routeFile) {
                $content = file($routeFile->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($content as $route) {
                    $parsedRoutes[] = $this->parseLine($route);
                }
            }

            foreach ($parsedRoutes as $route) {
                $this->routes[] = Route::fromArray($route);
            }
        }

        private function parseLine(string $line): array
        {
            $verbs = [];
            $url = '';
            $ctrl = [];
            $middleware = [];
            $name = null;

            $tokenizer = new Tokenizer($line);

            // first thing in line will be verb
            $availableVerbs = [
                'GET',
                'POST',
                'PUT',
                'DELETE',
                'OPTIONS',
            ];

            while (in_array($tokenizer->peekWord(), $availableVerbs)) {
                $verbs[] = $tokenizer->fetchWord();
            }

            $url = $tokenizer->fetchWord();

            if ($tokenizer->peekWord() === 'EXECUTE') {
                $tokenizer->ignoreWord();
                $tmp = $tokenizer->fetchWord();

                [$controller, $method] = explode('->', $tmp);
                if ($controller[0] === '$') {
                    $controller = sprintf(
                        '%s\\Http\\Controllers\\%sController',
                        $this->baseNamespace,
                        substr($controller, 1)
                    );
                }

                $ctrl = [$controller, $method];
            }

            if ($tokenizer->peekWord() === 'NAME') {
                $tokenizer->ignoreWord();

                $name = $tokenizer->fetchWord();
            }

            return [
                'name' => $name,
                'verbs' => $verbs,
                'url' => $url,
                'ctrl' => $ctrl,
                'middleware' => $middleware,
            ];
        }

        public function execute(string $method, string $uri): int
        {
            $_ = $this->timer->section('EXECUTE_ACTION_FOR_URI');

            if (!$this->loadedRoutes()) {
                $this->loadRoutes();
            }

            $route = $this->findMatchingRoute($method, $uri);
            $response = $this->executeRoute($method, $uri, $route);

            return $this->getReturnCodeFromResponse($response);
        }

        private function findMatchingRoute(string $method, string $uri): Route
        {
            $_ = $this->timer->section('MATCHING_ROUTE');
            /** @var Route $route */
            foreach ($this->routes as $route) {
                if ($route->match($method, $uri)) {
                    return $route;
                }
            }
        }

        private function getReturnCodeFromResponse($response): int
        {
            return 0;
        }

        private function executeRoute(string $method, string $uri, Route $route)
        {
            $_ = $this->timer->section('EXECUTING_ROUTE');

            $middlewares = $this->getMiddlewaresFromRoute($route);
            $middlewares = Arr::map($middlewares, fn($className) => $this->app->fetch($className));

            $request = $this->createRequest($method, $uri);
            $response = $this->executeRequest($request, $middlewares, $route);

            dump($request, $response);

            $this->pumpResponse($response);
            $code = $response->getStatusCode();

            return $code >= 200 && $code <= 299 ? 0 : 1;
        }

        private function getMiddlewaresFromRoute(Route $route)
        {
            $_ = $this->timer->section('MIDDLEWARE_FETCH');

            $routeMiddlewares = $route->getMiddlewares();
            $appMiddlewares = $this->getAppMiddlewares();
            $aliases = $this->getAppAliasOfMiddlewares();

            $routeMiddlewares = Arr::map(
                $routeMiddlewares,
                fn($className) => Arr::in($aliases, $className) ? $aliases[$className] : $className
            );

            $appMiddlewares = Arr::map(
                $appMiddlewares,
                fn($className) => Arr::in($aliases, $className) ? $aliases[$className] : $className
            );

            $allMiddlewares = Arr::unique(Arr::stackValues($routeMiddlewares, $appMiddlewares));

            $ignored= $this->getAppBlacklistedMiddlewares();
            $allMiddlewares = Arr::filter($allMiddlewares, fn($class) => Arr::in($ignored, $class));

            $allMiddlewares[] = LastMiddleware::class;
            $allMiddlewares = Arr::sort($allMiddlewares, function ($left, $right) {
                $leftPriority = $this->app->fetch(InjectorInterface::class)->inject([$left, 'getPriority']);
                $rightPriority = $this->app->fetch(InjectorInterface::class)->inject([$right, 'getPriority']);

                return $leftPriority <=> $rightPriority;
            });

            return $allMiddlewares;
        }

        private function getAppMiddlewares(): array
        {
            return $this->config->options()->get('http.middlewares.implied', []);
        }

        private function getAppBlacklistedMiddlewares(): array
        {
            return $this->config->options()->get('http.middlewares.ignored', []);
        }

        private function getAppAliasOfMiddlewares(): array
        {
            return $this->config->options()->get('http.middlewares.aliased', []);
        }

        private function createRequest(string $method, string $uri): Request
        {
            return Request::fromEnvironment($method, $uri);
        }

        private function executeRequest(Request $request, array $middlewares, Route $route)
        {
            $middlewares = Arr::map($middlewares, function ($m) use ($route) {
                if ($m instanceof RouteAwareMiddlewareInterface) {
                    $m->setRoute($route);
                }

                return $m;
            });

            $ctx = [
                MiddlewareContextName::CONTROLLER_OBJECT => $this->app->fetch($route->getControllerClass()),
                MiddlewareContextName::ACTION_NAME => $route->getControllerAction(),
            ];

            $nextMiddleware = new NextMiddleware($middlewares);
            return $nextMiddleware->next($request, $nextMiddleware, $ctx);
        }

        private function pumpResponse(Response $response): void
        {
        }
    }
