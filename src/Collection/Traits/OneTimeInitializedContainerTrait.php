<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Traits;

    trait OneTimeInitializedContainerTrait
    {
        private array $container = [];
        private bool $initialized = false;

        public function key()
        {
            if ($this->initialized) {
                return key($this->container);
            }

            return null;
        }

        public function current()
        {
            if ($this->initialized) {
                return current($this->container);
            }
        }

        public function next()
        {
            next($this->container);
        }

        public function valid()
        {
            if (!$this->initialized) {
                $this->container = $this->initializeContainer();

                reset($this->container);
                $this->initialized = true;
            }

            return !($this->key() === null && $this->current() === false);
        }

        public function rewind()
        {
            if ($this->initialized) {
                reset($this->container);
            }
        }
    }
