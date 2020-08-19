<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace DependencyInjector\ConcreteInjectorTest;

    use Mocks\PsychoB\WebFramework\DependencyInjection\ConstructorlessMock;
    use Mocks\PsychoB\WebFramework\DependencyInjection\HintedMethodMock;
    use Mocks\PsychoB\WebFramework\DependencyInjection\ScalarConstructorMock;
    use PsychoB\WebFramework\Core\Kernel;
    use PsychoB\WebFramework\DependencyInjector\Exceptions\UnknownApplicationHintException;
    use PsychoB\WebFramework\DependencyInjector\Hints\ApplicationHints;
    use PsychoB\WebFramework\DependencyInjector\Hints\HintInterface;
    use PsychoB\WebFramework\DependencyInjector\Injector\ConcreteInjector;
    use PsychoB\WebFramework\DependencyInjector\Injector\DependencyInjector;
    use PsychoB\WebFramework\DependencyInjector\Injector\InjectorInterface;
    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainer;
    use PsychoB\WebFramework\Testing\TestCase;

    class ParameterResolutionTest extends TestCase
    {
        private ConcreteInjector $injector;
        private ServiceContainer $container;

        protected function setUp(): void
        {
            parent::setUp();

            $this->container = new ServiceContainer();
            $this->injector = new ConcreteInjector($this->container);
            $this->container->register([InjectorInterface::class => new DependencyInjector($this->injector, $this->container)]);
        }

        public function testResolutionNoArguments(): void
        {
            $this->assertTrue($this->injector->inject(fn () => true));
        }

        public function testResolutionOnlySimpleClasses(): void
        {
            $this->assertTrue($this->injector->inject(fn (ConstructorlessMock $mock) => true));
        }

        public function testResolutionComplexDependency(): void
        {
            $this->container->set(ScalarConstructorMock::class, new ScalarConstructorMock(10));
            $this->assertSame(10, $this->injector->inject(fn (ScalarConstructorMock $mock) => $mock->foo));
        }

        public function testResolutionScalarWithNull(): void
        {
            $this->assertNull($this->injector->inject(fn (?int $mock) => $mock));
        }

        public function testResolutionDefaultValue(): void
        {
            $this->assertSame('foo', $this->injector->inject(fn ($mock = 'foo') => $mock));
        }

        public function testHintsOnMethods(): void
        {
            $appPath = 'app/path';
            $frameworkPath = 'framework/path';
            $this->container->set(
                Kernel::class,
                \Mockery::mock(Kernel::class)
                    ->shouldReceive('getApplicationPath')->andReturn($appPath)->getMock()
                    ->shouldReceive('getFrameworkPath')->andReturn($frameworkPath)->getMock()
            );
            $hinted = new HintedMethodMock();

            $this->assertSame($appPath, $this->injector->inject([$hinted, 'getAppPath']));
            $this->assertSame($frameworkPath, $this->injector->inject([$hinted, 'getFramePath']));
        }

        public function testInvalidHint(): void
        {
            $mock = \Mockery::mock(HintedMethodMock::class);
            $mockedHint = \Mockery::mock(HintInterface::class);
            $mockedHint->shouldReceive('getHint')->andReturn('ą/ę');

            $mock->shouldReceive('ppp_internal__GetHints')->andReturn([
                'invalidHint' => [
                    $mockedHint,
                ],
            ]);

            $this->expectException(UnknownApplicationHintException::class);
            $this->injector->inject([$mock, 'invalidHint']);
        }
    }
