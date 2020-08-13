<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection;

    use PsychoB\WebFramework\Collection\Iterator\FilterKeyIterator;
    use PsychoB\WebFramework\Collection\Iterator\FilterValueIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapKeyIterator;
    use PsychoB\WebFramework\Collection\Iterator\MapValueIterator;
    use PsychoB\WebFramework\Utility\Arr;

    class CollectionStream implements CollectionStreamInterface
    {
        private array $container;
        private array $filters;

        private bool $iterating = false;
        private \Iterator $iterator;

        public function __construct(array $container)
        {
            $this->container = $container;
            $this->filters = [];
        }

        public function filterKey($callable): CollectionStreamInterface
        {
            return $this->_append(FilterKeyIterator::class, $callable);
        }

        public function filterValue($callable): CollectionStreamInterface
        {
            return $this->_append(FilterValueIterator::class, $callable);
        }

        public function filterOutEmpty(): CollectionStreamInterface
        {
            return $this->_append(FilterValueIterator::class, fn ($value) => !empty($value));
        }

        public function mapKey($callable): CollectionStreamInterface
        {
            return $this->_append(MapKeyIterator::class, $callable);
        }

        public function mapValue($callable): CollectionStreamInterface
        {
            return $this->_append(MapValueIterator::class, $callable);
        }

        private function _append(string $class, $callable): CollectionStreamInterface
        {
            $this->filters[] = [$class, $callable];
            return $this;
        }

        private function iterate(): iterable
        {
            $tmp = $this->container;
            $this->container = [];

            if (Arr::count($this->filters) === 0 || Arr::count($tmp) === 0) {
                return new \ArrayIterator($tmp);
            } else {
                $cnt = new \ArrayIterator($tmp);

                foreach ($this->filters as $val) {
                    [$class, $callable] = $val;

                    $cnt = new $class($cnt, $callable);
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
