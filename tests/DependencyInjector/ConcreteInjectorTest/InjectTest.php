<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace DependencyInjector\ConcreteInjectorTest;

    use Mocks\PsychoB\WebFramework\DependencyInjection\InjectTestMock;
    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainer;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\InvalidCallableException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\MethodIsNotPublicException;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\MethodIsNotStaticException;
    use PsychoB\WebFramework\DependencyInjector\Injector\ConcreteInjector;
    use PsychoB\WebFramework\DependencyInjector\Injector\InjectorInterface;
    use PsychoB\WebFramework\Testing\TestCase;

    class InjectTest extends TestCase
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

        public function provideInjectFormat(): array
        {
            return [
                [InjectTestMock::class, 'argumentlessFunction', [], true],
                [InjectTestMock::class, 'simpleInts', [21, 37], 58],
            ];
        }

        /** @dataProvider provideInjectFormat */
        public function testInjectFormatArray($class, $method, array $arguments, $result): void
        {
            $this->assertSame($result, $this->injector->inject([$class, $method], $arguments));
        }

        /** @dataProvider provideInjectFormat */
        public function testInjectFormatString($class, $method, array $arguments, $result): void
        {
            $this->assertSame($result, $this->injector->inject(sprintf('%s::%s', $class, $method), $arguments));
        }

        public function testInjectInvalidArrayFormat(): void
        {
            $this->expectException(InvalidCallableException::class);

            $this->injector->inject([InjectTestMock::class, 'foo', 'bar']);
        }

        public function testInjectInvalidStringFormat(): void
        {
            $this->expectException(InvalidCallableException::class);

            $this->injector->inject('ąę::foo:bgar');
        }

        public function testInjectNonStaticFunctionIntoArray(): void
        {
            $this->expectException(MethodIsNotStaticException::class);

            $this->injector->inject([InjectTestMock::class, 'nonStaticMethod']);
        }

        public function testInjectNonStaticFunctionIntoString(): void
        {
            $this->expectException(MethodIsNotStaticException::class);

            $this->injector->inject(sprintf('%s::%s', InjectTestMock::class, 'nonStaticMethod'));
        }

        public function testInjectNonPublicMethodIntoArray(): void
        {
            $this->expectException(MethodIsNotPublicException::class);

            $this->injector->inject([InjectTestMock::class, 'privateStaticMethod']);
        }

        public function testInjectNonPublicMethodIntoString(): void
        {
            $this->expectException(MethodIsNotPublicException::class);

            $this->injector->inject(sprintf('%s::%s', InjectTestMock::class, 'privateStaticMethod'));
        }

        public function testInjectNonPublicMethodIntoObject(): void
        {
            $this->expectException(MethodIsNotPublicException::class);

            $this->injector->inject([new InjectTestMock(), 'privateStaticMethod']);
        }
    }
