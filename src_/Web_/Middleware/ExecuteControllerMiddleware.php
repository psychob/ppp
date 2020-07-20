<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Web_\Middleware;

    use PsychoB\WebFramework\Web_\Http\Request;
    use PsychoB\WebFramework\Web_\Http\Response;

    class ExecuteControllerMiddleware implements MiddlewareInterface
    {
        public function next(Request $request, MiddlewareInterface $next, array $ctx = []): Response
        {
            $ret = $ctx[MiddlewareContextName::CONTROLLER_OBJECT]->{$ctx[MiddlewareContextName::ACTION_NAME]}();

            if ($ret instanceof Response) {
                return $ret;
            }

            return new Response($ret ?? '');
        }

        public static function getPriority(): int
        {
            return PHP_INT_MAX - 1;
        }
    }
