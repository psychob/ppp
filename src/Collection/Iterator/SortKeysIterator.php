<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    use Iterator;
    use PsychoB\WebFramework\Collection\Enum\SortDirectionEnum;
    use PsychoB\WebFramework\Utility\Arr;

    class SortKeysIterator extends AbstractSortIterator implements StreamIteratorInterface
    {
        private Iterator $iterator;
        private int $direction;

        public function __construct(Iterator $iterator, int $direction)
        {
            $this->iterator = $iterator;
            $this->direction = $direction;
        }

        protected function initializeContainer(): array
        {
            $array = iterator_to_array($this->iterator);

            switch ($this->direction) {
                case SortDirectionEnum::ASCENDING:
                    ksort($array, SORT_ASC);
                    break;

                case SortDirectionEnum::DESCENDING:
                    ksort($array, SORT_DESC);
                    break;
            }

            return $array;
        }
    }
