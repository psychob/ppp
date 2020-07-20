<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Middleware;

    use PsychoB\WebFramework\Web_\Http\Request;
    use PsychoB\WebFramework\Web_\Http\Response;

    class NextMiddleware implements MiddlewareInterface
    {
        private int $it = 0;
        private array $middleware = [];

        /**
         * NextMiddleware constructor.
         *
         * @param array $middleware
         */
        public function __construct(array $middleware)
        {
            $this->middleware = $middleware;
        }

        public function next(Request $request, MiddlewareInterface $next, array $ctx = []): Response
        {
            $nextM = $this->middleware[$this->it++] ?? new LastMiddleware();
            return $next->next($request, $nextM, $ctx);
        }

        public static function getPriority(): int
        {
        }
    }
