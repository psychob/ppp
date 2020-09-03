<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Web\Http\Middlewares;

    use PsychoB\WebFramework\Utility\Arr;
    use PsychoB\WebFramework\Web\Enum\HttpCodeEnum;
    use PsychoB\WebFramework\Web\Enum\MiddlewarePriorityEnum;
    use PsychoB\WebFramework\Web\Http\Request;
    use PsychoB\WebFramework\Web\Http\RequestContext;
    use PsychoB\WebFramework\Web\Http\Response;

    class EnableForSpecificEnvironmentMiddleware implements MiddlewareInterface
    {
        public static function getDefaultPriority(): int
        {
            return MiddlewarePriorityEnum::HIGHEST;
        }

        private array $allowedEnvironments = [];

        public function __construct(array $environments)
        {
            $this->allowedEnvironments = $environments;
        }

        public function handleRequest(Request $request, MiddlewareInterface $next, RequestContext $context): Response
        {
            if (!Arr::in($this->allowedEnvironments, $context->app()->getEnvironment())) {
                return $context->router()->respondWithDefaultError(HttpCodeEnum::HTTP_NOT_FOUND);
            }

            return $next->handleRequest($request, $next, $context);
        }
    }
