<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http\Middleware;

    use PsychoB\WebFramework\Web\Routes\Http\Request;
    use PsychoB\WebFramework\Web\Routes\Http\Response;

    interface MiddlewareInterface
    {
        public static function getPriority(): int;

        public function handle(Request $request, MiddlewareInterface $next, array $ctx = []): Response;
    }
