<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Web\Http\Route;

    use Mockery;
    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Web\Enum\HttpMethodEnum;
    use PsychoB\WebFramework\Web\Http\Route\Builder\GroupRouteBuilder;
    use PsychoB\WebFramework\Web\Http\Route\Builder\RouteBuilderInterface;
    use PsychoB\WebFramework\Web\Http\Route\Builder\RouteGroupInterface;

    class BuilderTest extends TestCase
    {
        public function testCreatingRoutesDirectly(): void
        {
            $adder = Mockery::mock(RouteGroupInterface::class);

            $adder->shouldReceive('addRoute')->with([HttpMethodEnum::GET], '/foo', ['foo', 'get'], null);
            $adder->shouldReceive('addRoute')->with([HttpMethodEnum::POST], '/foo', ['foo', 'post'], null);
            $adder->shouldReceive('addRoute')->with([HttpMethodEnum::PUT], '/foo', ['foo', 'put'], null);
            $adder->shouldReceive('addRoute')->with([HttpMethodEnum::DELETE], '/foo', ['foo', 'delete'], null);

            $grouper = new GroupRouteBuilder($adder, '/');
            $grouper->get('/foo', ['foo', 'get']);
            $grouper->post('/foo', ['foo', 'post']);
            $grouper->put('/foo', ['foo', 'put']);
            $grouper->delete('/foo', ['foo', 'delete']);

            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::GET], '/foo', ['foo', 'get'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::POST], '/foo', ['foo', 'post'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::PUT], '/foo', ['foo', 'put'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::DELETE], '/foo', ['foo', 'delete'], null]);
        }

        public function testCreatingRoutesWithHierarchy(): void
        {
            $adder = Mockery::spy(RouteGroupInterface::class);

            $grouper = new GroupRouteBuilder($adder, '/');
            $grouper->routes(function (RouteBuilderInterface $router) {
                $router->get('/foo', ['foo', 'get']);
                $router->post('/foo', ['foo', 'post']);
                $router->put('/foo', ['foo', 'put']);
                $router->delete('/foo', ['foo', 'delete']);
            });

            $grouper->group('/img')->routes(function (RouteBuilderInterface $router) {
                $router->get('/foo', ['foo', 'get']);
                $router->post('/foo', ['foo', 'post']);
                $router->put('/foo', ['foo', 'put']);
                $router->delete('/foo', ['foo', 'delete']);
            });

            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::GET], '/foo', ['foo', 'get'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::POST], '/foo', ['foo', 'post'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::PUT], '/foo', ['foo', 'put'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::DELETE], '/foo', ['foo', 'delete'], null]);

            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::GET], '/img/foo', ['foo', 'get'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::POST], '/img/foo', ['foo', 'post'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::PUT], '/img/foo', ['foo', 'put'], null]);
            $adder->shouldHaveReceived('addRoute', [[HttpMethodEnum::DELETE], '/img/foo', ['foo', 'delete'], null]);
        }
    }
