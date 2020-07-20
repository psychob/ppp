<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    namespace PsychoB\WebFramework\Collection;

    use PsychoB\WebFramework\Utility\Arr;

    class Collection implements StreamInterface
    {
        private array $container = [];

        public function __construct(array $container)
        {
            $this->container = $container;
        }

        public function filter(callable $filter): StreamInterface
        {
            $this->container = array_filter(
                $this->container,
                fn($value, $key) => !$filter($value, $key),
                ARRAY_FILTER_USE_BOTH
            );

            return $this;
        }

        public function map(callable $mapper, int $opts = 0): StreamInterface
        {
            $ret = [];
            $it = 0;

            foreach ($this->container as $key => $value) {
                $oldKey = $key;
                $oldValue = $value;
                $newKey = $oldKey;

                $newValue = $mapper($value, $key);

                if (($opts & Opt::REWRITE_KEYS) === Opt::REWRITE_KEYS) {
                    $newKey = $key;
                } else if (($opts & Opt::RECOUNT_KEY) === Opt::RECOUNT_KEY) {
                    $newKey = $it++;
                }

                if (($opts & Opt::IGNORE_RETURN) === Opt::IGNORE_RETURN) {
                    $newValue = $oldValue;
                }

                $ret[$newKey] = $newValue;
            }

            $this->container = $ret;

            return $this;
        }

        public function sort(callable $sorter): StreamInterface
        {
            uasort($this->container, $sorter);
            return $this;
        }

        public function recountKeys(): StreamInterface
        {
            $this->container = Arr::recountKeys($this->container);
            return $this;
        }

        public function append($value): StreamInterface
        {
            $this->container[] = $value;
            return $this;
        }

        public function toArray(): array
        {
            return $this->container;
        }
    }
