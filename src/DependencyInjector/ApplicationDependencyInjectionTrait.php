<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector;

    use Psr\Container\ContainerInterface;
    use PsychoB\WebFramework\Core\ApplicationInterface;
    use PsychoB\WebFramework\Core\EnvironmentInterface;
    use PsychoB\WebFramework\Core\Kernel;
    use PsychoB\WebFramework\DependencyInjector\Container\ReadonlyServiceContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainer;
    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainerInterface;
    use PsychoB\WebFramework\DependencyInjector\Injector\ConcreteInjector;
    use PsychoB\WebFramework\DependencyInjector\Injector\DependencyInjector;
    use PsychoB\WebFramework\DependencyInjector\Injector\InjectorInterface;
    use PsychoB\WebFramework\Web\Application;
    use PsychoB\WebFramework\Web\Environment;

    trait ApplicationDependencyInjectionTrait
    {
        private InjectorInterface $injector;

        private function setUpDependencyInjection(Environment $env, Kernel $kernelObject): void
        {
            $container = new ServiceContainer([
                Application::class => $this,
                ApplicationInterface::class => $this,
                Environment::class => $env,
                EnvironmentInterface::class => $env,
                Kernel::class => $kernelObject,
            ], [
                ServiceContainer::class,
                ReadonlyServiceContainerInterface::class,
                ServiceContainerInterface::class,
            ]);

            $injector = new ConcreteInjector($container);
            $dependencyInjector = new DependencyInjector($injector, $container);

            $container->register([
                ConcreteInjector::class => $injector,
                DependencyInjector::class => $dependencyInjector,
                InjectorInterface::class => $dependencyInjector,
                ContainerInterface::class => $container->psr(),
            ]);

            $this->injector = $dependencyInjector;
        }

        public function make(string $class, array $arguments = []): object
        {
            return $this->injector->make($class, $arguments);
        }

        public function inject($callable, array $arguments = [])
        {
            return $this->injector->inject($callable, $arguments);
        }
    }
