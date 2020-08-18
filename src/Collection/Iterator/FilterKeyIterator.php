<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    class FilterKeyIterator extends AbstractFilterIterator implements StreamIteratorInterface
    {
        protected function filterOutElement($key, $current): bool
        {
            return ($this->callable)($key, $current);
        }
    }
