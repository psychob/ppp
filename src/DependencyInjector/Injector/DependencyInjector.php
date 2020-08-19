<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Injector;

    use PsychoB\WebFramework\DependencyInjector\Container\ServiceContainer;

    class DependencyInjector implements InjectorInterface
    {
        private InjectorInterface $injector;
        private ServiceContainer $container;

        public function __construct(
            ConcreteInjector $injector,
            ServiceContainer $container
        ) {
            $this->injector = $injector;
            $this->container = $container;
        }

        public function inject($callable, array $arguments = [])
        {
            return $this->injector->inject($callable, $arguments);
        }

        public function make(string $class, array $arguments = []): object
        {
            if (!$this->container->has($class)) {
                $obj = $this->injector->make($class, $arguments);

                $this->container->set($class, $obj);
            }

            return $this->container->get($class);
        }
    }
