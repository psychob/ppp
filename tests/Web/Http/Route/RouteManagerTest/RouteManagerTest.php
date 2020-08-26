<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\Web\Http\Route\RouteManagerTest;

    use PsychoB\WebFramework\Testing\TestCase;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteException;
    use PsychoB\WebFramework\Web\Exceptions\DuplicateRouteNameException;
    use PsychoB\WebFramework\Web\Http\Request;
    use PsychoB\WebFramework\Web\Http\Route\FilledRoute;
    use PsychoB\WebFramework\Web\Http\Route\RouteManager;

    class RouteManagerTest extends TestCase
    {
        private RouteManager $routeManager;

        protected function setUp(): void
        {
            parent::setUp();

            $this->routeManager = new RouteManager();
        }

        public function testAddRouteDuplicateName(): void
        {
            $this->expectException(DuplicateRouteNameException::class);

            $this->routeManager->addRoute(['GET'], '/', [RouteManagerTest::class, 'foo'], 'foo');
            $this->routeManager->addRoute(['GET'], '/bar', [RouteManagerTest::class, 'foo'], 'foo');
        }

        public function testAddRouteDuplicateRoute(): void
        {
            $this->expectException(DuplicateRouteException::class);

            $this->routeManager->addRoute(['GET'], '/', [RouteManagerTest::class, 'foo'], 'foo');
            $this->routeManager->addRoute(['GET'], '/', [RouteManagerTest::class, 'foo'], 'bar');
        }

        public function testAddRoute(): void
        {
            $this->routeManager->addRoute(['GET'], '/', [RouteManagerTest::class, 'foo']);
            $this->routeManager->addRoute(['GET'], '/foo', [RouteManagerTest::class, 'foo']);
            $this->routeManager->addRoute(['POST'], '/foo', [RouteManagerTest::class, 'foo']);

            $this->assertSame(3, $this->routeManager->getRouteCount());
        }

        public function testMatchRouteSimpleOne(): void
        {
            $this->routeManager->addRoute(['GET'], '/foo', [], 'simple');
            $request = new Request('GET', '/foo');

            $filled = $this->routeManager->matchRouteForRequest($request);

            $this->assertInstanceOf(FilledRoute::class, $filled);
            $this->assertSame('simple', $filled->getName());
            $this->assertSame($request, $filled->getRequest());
        }

        public function testMatchRouteSimpleMultiple(): void
        {
            $this->routeManager->addRoute(['DELETE'], '/foo', [], '1');
            $this->routeManager->addRoute(['POST'], '/foo', [], '2');
            $this->routeManager->addRoute(['GET'], '/foo/bar', [], '4');
            $this->routeManager->addRoute(['GET'], '/foo', [], '3');

            $request = new Request('GET', '/foo');

            $filled = $this->routeManager->matchRouteForRequest($request);

            $this->assertInstanceOf(FilledRoute::class, $filled);
            $this->assertSame('3', $filled->getName());
            $this->assertSame($request, $filled->getRequest());
        }

        public function testMatchRouteParameters(): void
        {
            $this->routeManager->addRoute(['GET'], '/static/{page?}', [], 'static');
            $this->routeManager->addRoute(['GET'], '/{page:any}/i', [], 'any');
            $this->routeManager->addRoute(['GET'], '/system/user/{user:uuid}/{action}', [], 'user');
            $this->routeManager->addRoute(['GET'], '/image/{imgId:int}.png', [], 'image');

            $request = new Request('GET', '/image/2137.png');

            $filled = $this->routeManager->matchRouteForRequest($request);

            $this->assertInstanceOf(FilledRoute::class, $filled);
            $this->assertSame('image', $filled->getName());
            $this->assertSame($request, $filled->getRequest());
            $this->assertEquals(['imgId' => 2137], $filled->getMatched());
        }

        public function testMatchRouteEmpty(): void
        {
        }
    }
