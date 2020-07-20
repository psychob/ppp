<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\DependencyInjector;

    use Psr\Container\ContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\ElementNotFoundException;
    use PsychoB\WebFramework\DependencyInjector\ReadonlyServiceContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\ServiceContainer;
    use PsychoB\WebFramework\DependencyInjector\ServiceContainerInterface;
    use PsychoB\WebFramework\Testing\TestCase;

    class ServiceContainerTest extends TestCase
    {
        public function testConstructorNoArgs(): void
        {
            $container = new ServiceContainer();

            $this->assertInstanceOf(ServiceContainer::class, $container);
            $this->assertInstanceOf(ReadonlyServiceContainerInterface::class, $container);
            $this->assertInstanceOf(ServiceContainerInterface::class, $container);
            $this->assertNotInstanceOf(ContainerInterface::class, $container);
        }

        public function testConstructorRegisterStuff(): void
        {
            $container = new ServiceContainer([
                'Foo' => $this,
            ]);

            $this->assertTrue($container->has('Foo'));
            $this->assertInstanceOf(static::class, $container->get('Foo'));
            $this->assertFalse($container->has(ServiceContainer::class));
        }

        public function testConstructorSelfRegister(): void
        {
            $container = new ServiceContainer([
                'Foo' => $this,
            ], [
                ServiceContainer::class,
            ]);

            $this->assertTrue($container->has('Foo'));
            $this->assertInstanceOf(static::class, $container->get('Foo'));
            $this->assertTrue($container->has(ServiceContainer::class));
        }

        public function testHas(): void
        {
            $container = new ServiceContainer([
                'Foo' => $this,
            ], [
                ServiceContainer::class,
            ]);

            $this->assertTrue($container->has('Foo'));
            $this->assertTrue($container->has(ServiceContainer::class));
        }

        public function testGetExisting(): void
        {
            $container = new ServiceContainer([
                    'Foo' => $this,
            ], [
                ServiceContainer::class,
            ]);

            $this->assertInstanceOf(static::class, $container->get('Foo'));
            $this->assertInstanceOf(ServiceContainer::class, $container->get(ServiceContainer::class));

            $this->assertSame($this, $container->get('Foo'));
            $this->assertSame($container, $container->get(ServiceContainer::class));
        }

        public function testGetMissing(): void
        {
            $container = new ServiceContainer([
                'Foo' => $this,
            ], [
                ServiceContainer::class,
            ]);

            $this->matchThrownException(
                fn() => $container->get(\Reflection::class),
                ElementNotFoundException::class,
                [
                    'getElements' => ['Foo', ServiceContainer::class],
                    'getKey' => \Reflection::class,
                ]
            );
        }

        public function testGetOrExisting(): void
        {
            $container = new ServiceContainer([
                'Foo' => $this,
            ], [
                ServiceContainer::class,
            ]);

            $this->assertInstanceOf(static::class, $container->getOr('Foo', 1));
            $this->assertInstanceOf(ServiceContainer::class, $container->getOr(ServiceContainer::class, 2));

            $this->assertSame($this, $container->getOr('Foo', 3));
            $this->assertSame($container, $container->getOr(ServiceContainer::class, 4));
        }

        public function testGetOrMissing(): void
        {
            $container = new ServiceContainer();

            $this->assertNull($container->getOr('Foo', null));
            $this->assertNull($container->getOr(ServiceContainer::class, null));
        }

        public function testSet(): void
        {
            $container = new ServiceContainer();

            $this->assertFalse($container->has(ServiceContainer::class));
            $container->set(ServiceContainer::class, $container);
            $this->assertTrue($container->has(ServiceContainer::class));
            $this->assertInstanceOf(ServiceContainer::class, $container->get(ServiceContainer::class));
            $this->assertSame($container, $container->get(ServiceContainer::class));
        }

        public function testRegister(): void
        {
            $container = new ServiceContainer();

            $this->assertFalse($container->has(ServiceContainer::class));
            $this->assertFalse($container->has(ReadonlyServiceContainerInterface::class));

            $container->register([
                ServiceContainer::class => $container,
                ReadonlyServiceContainerInterface::class => $container,
            ]);

            $this->assertTrue($container->has(ServiceContainer::class));
            $this->assertTrue($container->has(ReadonlyServiceContainerInterface::class));
        }

        public function testPsr(): void
        {
            $container = new ServiceContainer();

            $this->assertInstanceOf(ContainerInterface::class, $container->psr());
        }
    }
