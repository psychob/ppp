<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http\Middleware;

    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Web\Routes\Http\Request;
    use PsychoB\WebFramework\Web\Routes\Http\Response;
    use PsychoB\WebFramework\Web\Routes\Route;

    class MiddlewareExecutor implements MiddlewareInterface
    {
        protected array $middleware = [];
        protected Route $route;

        public function __construct(array $middleware, Route $route)
        {
            $this->middleware = $middleware;
            $this->route = $route;
        }

        public static function getPriority(): int
        {
        }

        public function handle(Request $request, MiddlewareInterface $next, array $ctx = []): Response
        {
        }

        public function start(Request $request): Response
        {
            return Arr::first($this->middleware)->handle($request, $this, [
                self::class => 1,
                Route::class => $this->route,
                'suggested_arguments' => [],
            ]);
        }
    }
