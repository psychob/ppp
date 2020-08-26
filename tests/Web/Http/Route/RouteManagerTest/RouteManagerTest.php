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
    }
