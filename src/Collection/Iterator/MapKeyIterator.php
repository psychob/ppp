<?php
    /*
     * ppp
     * (c) 2020 Andrzej Budzanowski <kontakt@andrzej.budzanowski.pl>
     */

    declare(strict_types=1);

    namespace PsychoB\WebFramework\Collection\Iterator;

    class MapKeyIterator extends AbstractMapIterator implements StreamIteratorInterface
    {
        protected function mapElement($key, $current): array
        {
            return [
                ($this->callable)($key, $current),
                $current,
            ];
        }
    }
