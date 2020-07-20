<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\DependencyInjector;

    use PsychoB\WebFramework\Container\ContainerInterface;
    use PsychoB\WebFramework\Injector\InjectorInterface;

    class DynamicInjector
    {
        protected ContainerInterface $container;
        protected InjectorInterface $injector;

        /**
         * DynamicInjector constructor.
         *
         * @param ContainerInterface $container
         * @param InjectorInterface  $injector
         */
        public function __construct(ContainerInterface $container, InjectorInterface $injector)
        {
            $this->container = $container;
            $this->injector = $injector;

            $this->container->set(DynamicInjector::class, $this);
        }

        public function fetch(string $class): object
        {
            if ($this->container->has($class)) {
                return $this->container->get($class);
            }

            $object = $this->injector->make($class);

            if (!($object instanceof DoNotCacheServiceHint)) {
                $this->container->set($class, $object);
            }

            return $object;
        }
    }
