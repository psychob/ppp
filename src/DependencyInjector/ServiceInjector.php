<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\DependencyInjector;

    use PsychoB\WebFramework\Container\ServiceContainer;
    use PsychoB\WebFramework\DependencyInjector\Hints\DoNotCacheInServiceContainerHint;

    class ServiceInjector implements InjectorInterface
    {
        private ServiceContainer $container;

        public function __construct(ServiceContainer $container)
        {
            $this->container = $container;
            $this->container->set(Injector::class, new Injector($this->container, $this));
        }

        public function inject($callable, array $arguments = [])
        {
            return $this->container->get(Injector::class)->inject($callable, $arguments);
        }

        public function make(string $class, array $arguments = []): object
        {
            if ($this->container->has($class)) {
                return $this->container->get($class);
            }

            /** @var InjectorInterface $injector */
            $injector = $this->container->get(Injector::class);
            $object = $injector->make($class, $arguments);

            if (!($object instanceof DoNotCacheInServiceContainerHint)) {
                $this->container->set($class, $object);
            }

            return $object;
        }

        public function getContainer(): ServiceContainer
        {
            return $this->container;
        }
    }
