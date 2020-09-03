<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection;

    use ArrayIterator;
    use Iterator;
    use PsychoB\WebFramework\Collection\Traits\CollectionFilterTrait;
    use PsychoB\WebFramework\Utility\Arr;

    class CollectionStream implements CollectionStreamInterface
    {
        use CollectionFilterTrait;

        private array $container;

        private bool $iterating = false;
        private Iterator $iterator;

        public function __construct(array $container)
        {
            $this->container = $container;
            $this->filters = [];
        }

        private function iterate(): iterable
        {
            $tmp = $this->container;
            $this->container = [];

            if (Arr::count($this->filters) === 0 || Arr::count($tmp) === 0) {
                return new ArrayIterator($tmp);
            } else {
                $cnt = new ArrayIterator($tmp);

                foreach ($this->filters as $val) {
                    [$class, $arguments] = $val;

                    $cnt = new $class($cnt, ...$arguments);
                }

                return $cnt;
            }
        }

        public function toArray(): array
        {
            return Arr::toArray($this);
        }

        public function current()
        {
            return $this->iterator ? $this->iterator->current() : null;
        }

        public function key()
        {
            return $this->iterator ? $this->iterator->key() : null;
        }

        public function next(): void
        {
            $this->iterator->next();
        }

        public function valid(): bool
        {
            if ($this->iterator) {
                if ($this->iterator->valid()) {
                    $this->container[$this->key()] = $this->current();

                    return true;
                }

                $this->iterating = false;
            }

            return false;
        }

        public function rewind()
        {
            if ($this->iterating) {
                // we must finish our initial iteration
                while ($this->valid()) {
                    $this->next();
                }
            }

            /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
            $this->iterator = $this->iterate();
            $this->iterating = true;

            $this->iterator->rewind();
        }

        public function count(): int
        {
            if (Arr::len($this->filters) === 0) {
                return count($this->container);
            }

            return count(Arr::toArray($this));
        }
    }
