<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Container;

    use Psr\Container\ContainerInterface as PsrContainerInterface;

    class ServiceContainer implements ServiceContainerInterface
    {
        private array $container = [];

        public function get(string $class)
        {
            return $this->container[$class];
        }

        public function getOr(string $class, $default)
        {
            // TODO: Implement getOr() method.
        }

        public function has(string $class): bool
        {
            return array_key_exists($class, $this->container);
        }

        public function set(string $class, $value): void
        {
            $this->container[$class] = $value;

            ksort($this->container);
        }

        public function psr(): PsrContainerInterface
        {
            return new PsrContainerAdapter($this);
        }

        /**
         * @param object[] $services
         */
        public function registerAll(array $services): void
        {
            foreach ($services as $name => $object) {
                $this->set($name, $object);
            }
        }
    }
