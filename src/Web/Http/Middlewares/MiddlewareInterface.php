<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Middlewares;

    use PsychoB\WebFramework\Web\Http\Request;
    use PsychoB\WebFramework\Web\Http\RequestContext;
    use PsychoB\WebFramework\Web\Http\Response;

    interface MiddlewareInterface
    {
        public static function getDefaultPriority(): int;

        public function handleRequest(Request $request, MiddlewareInterface $next, RequestContext $context): Response;
    }
