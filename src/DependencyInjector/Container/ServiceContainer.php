<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\DependencyInjector\Container;

    use PsychoB\WebFramework\DependencyInjector\Exceptions\ElementNotFoundException;

    class ServiceContainer implements ReadonlyServiceContainerInterface, ServiceContainerInterface
    {
        use ServiceContainerPsrCacheTrait;

        private array $container = [];

        public function __construct(array $container = [], ?array $selfRegister = null)
        {
            foreach ($container as $name => $value) {
                $this->set($name, $value);
            }

            if ($selfRegister) {
                foreach ($selfRegister as $value) {
                    $this->container[$value] = $this;
                }
            }

            ksort($this->container);
        }

        /** @inheritDoc */
        public function has(string $key): bool
        {
            return array_key_exists($key, $this->container);
        }

        /** @inheritDoc */
        public function get(string $key): object
        {
            if (!$this->has($key)) {
                throw new ElementNotFoundException(array_keys($this->container), $key);
            }

            return $this->container[$key];
        }

        /** @inheritDoc */
        public function getOr(string $key, $default = null)
        {
            if (!$this->has($key)) {
                return $default;
            }

            return $this->container[$key];
        }

        /** @inheritDoc */
        public function set(string $key, object $value): void
        {
            $this->container[$key] = $value;

            ksort($this->container);
        }

        /** @inheritDoc */
        public function register(array $elements): void
        {
            foreach ($elements as $name => $value) {
                $this->set($name, $value);
            }
        }
    }
