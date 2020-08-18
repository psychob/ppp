<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;

    abstract class AbstractFilterIterator implements StreamIteratorInterface
    {
        /** @var Iterator */
        protected Iterator $iterable;
        /** @var callable */
        protected $callable;

        abstract protected function filterOutElement($key, $current): bool;

        public function __construct(Iterator $iterator, callable $callable)
        {
            $this->iterable = $iterator;
            $this->callable = $callable;
        }

        public function current()
        {
            return $this->iterable->current();
        }

        public function next()
        {
            $this->iterable->next();
        }

        public function key()
        {
            return $this->iterable->key();
        }

        public function valid()
        {
            while ($this->iterable->valid() && !$this->filterOutElement($this->key(), $this->current())) {
                $this->iterable->next();
            }

            return $this->iterable->valid();
        }

        public function rewind()
        {
            $this->iterable->rewind();
        }
    }
