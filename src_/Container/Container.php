<?php
    //
    // GameLibrary
    // (c) 2020 RGB Lighthouse <https://rgblighthouse.pl>
    // (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
    //

    namespace PsychoB\WebFramework\Container;

    class Container implements ContainerInterface
    {
        protected array $container;

        /**
         * Container constructor.
         */
        public function __construct()
        {
            $this->container = [
                Container::class => $this,
                ContainerInterface::class => $this,
            ];
        }

        public function get(string $id): object
        {
            if (!$this->has($id)) {
                throw new ElementNotFoundException($id, array_keys($this->container));
            }

            return $this->container[$id];
        }

        public function set(string $id, object $value): void
        {
            $this->container[$id] = $value;

            ksort($this->container);
        }

        public function has(string $id): bool
        {
            return array_key_exists($id, $this->container);
        }
    }
