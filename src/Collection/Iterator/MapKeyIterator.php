<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;

    class MapKeyIterator implements StreamIteratorInterface
    {
        protected Iterator $iterable;

        /** @var callable */
        protected $filter;

        private $currentKey;
        private $currentValue;

        public function __construct(Iterator $iterable, callable $filterFnc)
        {
            $this->iterable = $iterable;
            $this->filter = $filterFnc;
        }

        public function current()
        {
            return $this->currentValue;
        }

        public function next()
        {
            $this->iterable->next();
        }

        public function key()
        {
            return $this->currentKey;
        }

        public function valid()
        {
            $ret = $this->iterable->valid();

            if ($ret) {
                $this->currentKey = ($this->filter)($this->iterable->key(), $this->iterable->current());
                $this->currentValue = $this->iterable->current();
            } else {
                $this->currentKey = null;
                $this->currentValue = null;
            }

            return $ret;
        }

        public function rewind()
        {
            $this->iterable->rewind();
        }
    }
