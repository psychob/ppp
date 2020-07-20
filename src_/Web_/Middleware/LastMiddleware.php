<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Middleware;

    use PsychoB\WebFramework\Web_\Http\Request;
    use PsychoB\WebFramework\Web_\Http\Response;

    class LastMiddleware implements MiddlewareInterface
    {
        public function next(Request $request, MiddlewareInterface $next, array $ctx = []): Response
        {
            throw new UnreachableException('This middleware should never be reached');
        }

        public static function getPriority(): int
        {
            return PHP_INT_MAX;
        }
    }
