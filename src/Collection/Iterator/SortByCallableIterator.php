<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;
    use PsychoB\WebFramework\Utility\Arr;

    class SortByCallableIterator extends AbstractSortIterator implements StreamIteratorInterface
    {
        private Iterator $iterator;
        private $sortedFnc;
        private bool $preserveKeys;

        /**
         * SortByCallableIterator constructor.
         *
         * @param Iterator $iterator
         * @param callable $sortedFnc
         * @param bool      $preserveKeys
         */
        public function __construct(Iterator $iterator, callable $sortedFnc, bool $preserveKeys)
        {
            $this->iterator = $iterator;
            $this->sortedFnc = $sortedFnc;
            $this->preserveKeys = $preserveKeys;
        }

        protected function initializeContainer(): array
        {
            $array = iterator_to_array($this->iterator);

            uasort($array, $this->sortedFnc);

            if ($this->preserveKeys) {
                return $array;
            }

            return Arr::values($array);
        }
    }
