<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;

    class FilterValueIterator implements StreamIteratorInterface
    {
        protected Iterator $iterable;

        /** @var callable */
        protected $filter;

        public function __construct(Iterator $iterable, callable $filterFnc)
        {
            $this->iterable = $iterable;
            $this->filter = $filterFnc;
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
            while ($this->iterable->valid() && !($this->filter)($this->current(), $this->key())) {
                $this->iterable->next();
            }

            return $this->iterable->valid();
        }

        public function rewind()
        {
            $this->iterable->rewind();
        }
    }
