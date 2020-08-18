<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;

    abstract class AbstractMapIterator implements StreamIteratorInterface
    {
        /** @var Iterator */
        protected Iterator $iterable;
        /** @var callable */
        protected $callable;
        /** @var mixed */
        protected $currentKey;
        /** @var mixed */
        protected $currentValue;

        abstract protected function mapElement($key, $current): array;

        public function __construct(Iterator $iterator, callable $callable)
        {
            $this->iterable = $iterator;
            $this->callable = $callable;
        }

        public function key()
        {
            return $this->currentKey;
        }

        public function current()
        {
            return $this->currentValue;
        }

        public function next()
        {
            $this->iterable->next();
        }

        public function valid()
        {
            $ret = $this->iterable->valid();

            if ($ret) {
                [$this->currentKey, $this->currentValue] = $this->mapElement($this->iterable->key(), $this->iterable->current());
            } else {
                [$this->currentKey, $this->currentValue] = [null, null];
            }

            return $ret;
        }

        public function rewind()
        {
            $this->iterable->rewind();
        }
    }
