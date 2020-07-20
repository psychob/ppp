<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Middleware;

    use PsychoB\WebFramework\Web_\Http\Request;
    use PsychoB\WebFramework\Web_\Http\Response;

    interface MiddlewareInterface
    {
        public function next(Request $request, MiddlewareInterface $next, array $ctx = []): Response;
        public static function getPriority(): int;
    }
