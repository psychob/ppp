<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace Tests\PsychoB\WebFramework\DependencyInjector\ConcreteInjectorTest;

    use Mocks\PsychoB\WebFramework\DependencyInjection\ConstructorlessMock;
    use Mocks\PsychoB\WebFramework\DependencyInjection\HaveConstructorMock;
    use Mocks\PsychoB\WebFramework\DependencyInjection\MultipleConstructorArgMock;
    use Mocks\PsychoB\WebFramework\DependencyInjection\ScalarConstructorMock;
    use Mocks\PsychoB\WebFramework\DependencyInjection\SimpleConstructorMock;
    use PsychoB\WebFramework\DependencyInjector\Injector\ConcreteInjector;
    use PsychoB\WebFramework\DependencyInjector\Injector\InjectorInterface;
    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainer;
    use PsychoB\WebFramework\Testing\TestCase;

    class MakeTest extends TestCase
    {
        private ConcreteInjector $injector;
        private ServiceContainer $container;

        protected function setUp(): void
        {
            parent::setUp();

            $this->container = new ServiceContainer();
            $this->injector = new ConcreteInjector($this->container);
            $this->container->register([InjectorInterface::class => $this->injector]);
        }

        public function provideConstructingClass(): array
        {
            return [
                [ConstructorlessMock::class],
                [HaveConstructorMock::class],
                [SimpleConstructorMock::class],
                [MultipleConstructorArgMock::class],
                [ScalarConstructorMock::class, [21]],
                [ScalarConstructorMock::class, ['foo' => 37]],
            ];
        }

        /** @dataProvider provideConstructingClass */
        public function testConstructingClass(string $class, array $arguments = []): void
        {
            $mock = $this->injector->make($class, $arguments);
            $this->assertInstanceOf($class, $mock);

            // we don't cache object when using this injector
            $mock2 = $this->injector->make($class, $arguments);
            $this->assertNotSame($mock, $mock2);
        }

        /** @dataProvider provideConstructingClass */
        public function testConstructingClassIndirectArray(string $class, array $arguments = []): void
        {
            $mock = $this->injector->inject([$class, '__construct'], $arguments);
            $this->assertInstanceOf($class, $mock);
        }

        /** @dataProvider provideConstructingClass */
        public function testConstructingClassIndirectString(string $class, array $arguments = []): void
        {
            $mock = $this->injector->inject(sprintf('%s::__construct', $class), $arguments);
            $this->assertInstanceOf($class, $mock);
        }
    }
