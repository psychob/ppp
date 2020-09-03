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
    use PsychoB\WebFramework\Utility\Path;

    class SortIterator extends AbstractSortIterator implements StreamIteratorInterface
    {
        private Iterator $iterator;
        private int $direction;
        private bool $preserveKeys;

        public function __construct(Iterator $iterator, int $direction, bool $preserveKeys)
        {
            $this->iterator = $iterator;
            $this->direction = $direction;
            $this->preserveKeys = $preserveKeys;
        }

        protected function initializeContainer(): array
        {
            $array = iterator_to_array($this->iterator);

            switch ($this->direction) {
                case SortDirectionEnum::ASCENDING:
                    asort($array, SORT_ASC);
                    break;

                case SortDirectionEnum::DESCENDING:
                    asort($array, SORT_DESC);
                    break;
            }

            if ($this->preserveKeys) {
                return $array;
            }

            return Arr::values($array);
        }
    }
