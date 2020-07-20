<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Web\Routes\Http\Middleware;

    use PsychoB\WebFramework\DependencyInjector\Hints\ConstructorHint;
    use PsychoB\WebFramework\DependencyInjector\Hints\InjectorHint;
    use PsychoB\WebFramework\DependencyInjector\InjectorInterface;
    use PsychoB\WebFramework\Web\Routes\Http\Request;
    use PsychoB\WebFramework\Web\Routes\Http\Response;
    use PsychoB\WebFramework\Web\Routes\Route;

    class ConstructResponseMiddleware implements MiddlewareInterface, ConstructorHint
    {
        protected InjectorInterface $injector;

        public function __construct(InjectorInterface $injector)
        {
            $this->injector = $injector;
        }

        public static function getPriority(): int
        {
            return PHP_INT_MAX;
        }

        public function handle(Request $request, MiddlewareInterface $next, array $ctx = []): Response
        {
            /** @var Route $route */
            $route = $ctx[Route::class];

            $ctrl = $this->injector->make($route->getController());
            $response = $this->injector->inject([$ctrl, $route->getAction()], $ctx['suggested_arguments']);

            if (!($response instanceof Response)) {
                $response = new Response();
            }

            return $response;
        }

        public static function ppp_internal__GetConstructorHint(): array
        {
            return [
                'route' => InjectorHint::currentRoute(),
            ];
        }
    }
